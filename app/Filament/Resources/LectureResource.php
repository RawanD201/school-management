<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Level;
use App\Models\Lecture;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Livewire\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LectureResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LectureResource\RelationManagers;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Card::make()
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('attr.name'))
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
                        Components\FileUpload::make('file')
                            ->label(__('attr.file'))
                            ->directory('lectures')
                            ->multiple()
                            ->disk('public')
                            ->required()
                            ->enableDownload()
                            ->preserveFilenames()
                            ->enableOpen()
                            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                return (string) str($file->getClientOriginalName())->prepend(now()->timestamp);
                            })
                    ])->columns(),
            ]);
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('attr.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label(__('attr.level'))
                    ->searchable()
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLectures::route('/'),
            'create' => Pages\CreateLecture::route('/create'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }

    protected static function getTitle(): string
    {
        return trans_choice('lecture', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }
}
