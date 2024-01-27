<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Gender;
use App\Models\Level;
use Faker\Core\Blood;
use App\Models\Lecture;
use App\Models\Teacher;
use App\Enums\BloodType;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use App\Models\TeacherLessonStudied;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput\Mask;
use App\Filament\Resources\TeacherResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherExamRelationManager;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherPrizeRelationManager;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherLectureRelationManager;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherActivityRelationManager;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('fullname')
                                    ->label(__('attr.fullname'))
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(255),
                                Forms\Components\Select::make('gender')
                                    ->label(__('attr.gender'))
                                    ->options(Gender::display())
                                    ->enum(Gender::class)
                                    ->required(),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('attr.phone'))
                                    ->tel()
                                    ->required()
                                    ->maxLength(255)
                                    ->mask(fn (Mask $mask) => $mask->pattern('00000000000')),
                                Forms\Components\TextInput::make('security_code')
                                    ->label(__('attr.security_code'))
                                    ->required()
                                    ->minLength(8)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('national_identity_number')
                                    ->label(__('attr.national_identity_number'))
                                    ->required()
                                    ->numeric()
                                    ->minLength(2)
                                    ->maxLength(255),
                                Forms\Components\Select::make('blood_type')
                                    ->label(__('attr.blood_type'))
                                    ->options(BloodType::display())
                                    ->enum(BloodType::class)
                                    ->required(),
                                Forms\Components\Select::make('level')
                                    ->label(__('attr.level'))
                                    ->required()
                                    ->options(fn () => Level::all()->pluck('name', 'name'))
                                    ->searchable()
                                    ->getSearchResultsUsing(
                                        fn (string $search) => Level::where('name', 'LIKE', "{$search}%")->pluck('name', 'id')
                                    )
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('attr.name'))
                                            ->maxLength(255)
                                    ])
                                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                        return $action
                                            ->modalHeading(__('labels.level.create'))
                                            ->modalWidth('md');
                                    })
                                    ->createOptionUsing(function (array $data) {
                                        $record = Level::create($data);
                                        return $record->id;
                                    }),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label(__('attr.start_date'))
                                    ->firstDayOfWeek(6)
                                    ->displayFormat('d/m/Y')
                                    ->default(now()),
                                Forms\Components\Select::make('lessons_studied')
                                    ->label(__('attr.lessons_studied'))
                                    ->options(fn () => TeacherLessonStudied::all()->pluck('name', 'name'))
                                    ->searchable()
                                    ->multiple()
                                    ->getSearchResultsUsing(
                                        fn (string $search) => TeacherLessonStudied::where('name', 'LIKE', "{$search}%")->pluck('name', 'id')
                                    )
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('attr.name'))
                                            ->maxLength(255)
                                    ])
                                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                        return $action
                                            ->modalHeading(__('labels.level.create'))
                                            ->modalWidth('md');
                                    })
                                    ->createOptionUsing(function (array $data) {
                                        $record = TeacherLessonStudied::create($data);
                                        return $record->id;
                                    }),
                            ])->columns(),
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\MarkdownEditor::make('note')
                                    ->label(__('attr.note'))
                                    ->disableAllToolbarButtons(),
                            ])
                    ])->columnSpan(['lg' => 1]),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->grow(false)
                    ->label(__('index'))
                    ->getStateUsing(
                        static fn (\stdClass $rowLoop): string => (string) $rowLoop->iteration
                    )
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('id'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullname')
                    ->label(__('attr.fullname'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('gender')
                    ->label(__('attr.gender'))
                    ->color('primary')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('attr.phone'))
                    ->icon('heroicon-o-phone')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('security_code')
                    ->label(__('attr.security_code'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('national_identity_number')
                    ->label(__('attr.national_identity_number'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('blood_type')
                    ->label(__('attr.blood_type'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('level')
                    ->label(__('attr.level'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('attr.start_date'))
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('lessons_studied')
                    ->label(__('attr.lessons_studied'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('attr.note'))
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(fn (Model $record) => str($record->note)->limit(20)->toString())
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('attr.created_at'))
                    ->icon('heroicon-o-clock')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('attr.updated_at'))
                    ->icon('heroicon-o-refresh')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('blood_type')
                    ->label(__('attr.blood_type'))
                    ->options(BloodType::display()),
                Tables\Filters\Filter::make('date')
                    ->label(__('date'))
                    ->indicator(__('date'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label(__('filters.created_from')),
                        Forms\Components\DatePicker::make('created_until')
                            ->label(__('filters.created_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('prize')
                    ->label(__('actions.prize'))
                    ->icon('heroicon-o-cube')
                    ->color('success')
                    ->url(
                        fn (Teacher $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 0,
                        ])

                    ),
                Tables\Actions\Action::make('exam')
                    ->label(__('actions.exam'))
                    ->icon('heroicon-o-link')
                    ->color('danger')
                    ->url(
                        fn (teacher $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 1,
                        ])
                    ),
                Tables\Actions\Action::make('activity')
                    ->label(__('actions.activity'))
                    ->color('other')
                    ->icon('heroicon-o-map')
                    ->url(
                        fn (teacher $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 3,
                        ])
                    ),
                Tables\Actions\Action::make('lecture')
                    ->label(__('actions.lecture'))
                    ->color('warning')
                    ->icon('heroicon-o-book-open')
                    ->url(
                        fn (teacher $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 4,
                        ])
                    ),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TeacherPrizeRelationManager::class,
            TeacherExamRelationManager::class,
            TeacherActivityRelationManager::class,
            TeacherLectureRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }

    protected static function getTitle(): string
    {
        return trans_choice('teacher', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }
}
