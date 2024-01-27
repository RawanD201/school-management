<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Gender;
use App\Models\Level;
use App\Models\Classes;
use App\Models\Student;
use App\Enums\BloodType;
use Filament\Resources\Form;
use Filament\Tables\Filters;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput\Mask;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\StudentExamRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\StudentPrizeRelationManager;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;


    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('user')
                        ->label(__('tabs.user_info'))
                        ->icon('heroicon-o-clipboard-list')
                        ->schema([
                            Card::make()
                                ->schema([
                                    TextInput::make('fullname')
                                        ->label(__('attr.fullname'))
                                        ->required()
                                        ->minLength(2)
                                        ->maxLength(255),
                                    TextInput::make('age')
                                        ->label(__('attr.age'))
                                        ->minLength(2)
                                        ->numeric()
                                        ->maxLength(100)
                                        ->required(),
                                    Select::make('gender')
                                        ->label(__('attr.gender'))
                                        ->options(Gender::display())
                                        ->enum(Gender::class)
                                        ->required(),
                                    TextInput::make('phone')
                                        ->label(__('attr.phone'))
                                        ->tel()
                                        ->required()
                                        ->maxLength(255)
                                        ->mask(fn (Mask $mask) => $mask->pattern('00000000000')),
                                    TextInput::make('father_occupation')
                                        ->label(__('attr.father_occupation')),
                                    TextInput::make('mother_occupation')
                                        ->label(__('attr.mother_occupation')),
                                    TextInput::make('address')
                                        ->label(__('attr.address')),
                                    TextInput::make('address')
                                        ->label(__('attr.sickness')),
                                ])
                                ->columns(4)

                        ]),
                    Wizard\Step::make('profile')
                        ->icon('heroicon-o-user')
                        ->label(__('tabs.identity'))
                        ->schema([
                            Card::make()
                                ->schema([
                                    Select::make('blood_type')
                                        ->label(__('attr.blood_type'))
                                        ->options(BloodType::display())
                                        ->enum(BloodType::class)
                                        ->required(),
                                    TextInput::make('father_educational_level')
                                        ->label(__('attr.father_educational_level')),
                                    TextInput::make('note')
                                        ->label(__('attr.note')),
                                    TextInput::make('exam_score')
                                        ->numeric()
                                        ->label(__('attr.exam_score')),
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
                                            // Return key item for option
                                            return $record->id;
                                        }),
                                    Forms\Components\Select::make('class')
                                        ->label(__('attr.class'))
                                        ->required()
                                        ->options(fn () => Classes::all()->pluck('name', 'name'))
                                        ->searchable()
                                        ->getSearchResultsUsing(
                                            fn (string $search) => Classes::where('name', 'LIKE', "{$search}%")->pluck('name', 'id')
                                        )
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->label(__('attr.name'))
                                                ->maxLength(255)
                                        ])
                                        ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                            return $action
                                                ->modalHeading(__('labels.class.create'))
                                                ->modalWidth('md');
                                        })
                                        ->createOptionUsing(function (array $data) {
                                            $record = Classes::create($data);
                                            return $record->id;
                                        }),
                                ])
                                ->columns(4)
                        ]),
                ]),
            ])->columns(1);
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
                Tables\Columns\TextColumn::make('age')
                    ->label(__('attr.age'))
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
                Tables\Columns\TextColumn::make('father_occupation')
                    ->label(__('attr.father_occupation'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mother_occupation')
                    ->label(__('attr.mother_occupation'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('attr.address'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sickness')
                    ->label(__('attr.sickness'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('blood_type')
                    ->label(__('attr.blood_type'))
                    ->color('primary')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father_educational_level')
                    ->label(__('attr.father_educational_level'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('attr.note'))
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(fn (Model $record) => str($record->note)->limit(20)->toString())
                    ->sortable(),
                Tables\Columns\TextColumn::make('exam_score')
                    ->label(__('attr.exam_score'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('attr.level'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('class')
                    ->label(__('attr.class'))
                    ->color('primary')
                    ->searchable()
                    ->toggleable()
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
                        fn (Student $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 0,
                        ])

                    ),
                Tables\Actions\Action::make('exam')
                    ->label(__('actions.exam'))
                    ->icon('heroicon-o-link')
                    ->color('danger')
                    ->url(
                        fn (Student $record) => static::getUrl('edit', [
                            'record' => $record->id,
                            'activeRelationManager' => 1,
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
            StudentPrizeRelationManager::class,
            StudentExamRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public function hasCombinedRelationManagerTabsWithForm(): bool
    {
        return true;
    }


    protected static function getTitle(): string
    {
        return trans_choice('student', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }
}
