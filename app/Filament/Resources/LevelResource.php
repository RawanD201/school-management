<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Level;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components;
use App\Filament\Resources\LevelResource\Pages;

class LevelResource extends Resource
{
    protected static ?string $model = Level::class;

    protected ?string $maxContentWidth = 'lg';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Card::make()
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('attr.name'))
                            ->required(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLevels::route('/'),
            // 'create' => Pages\CreateLevel::route('/create'),
            // 'edit' => Pages\EditLevel::route('/{record}/edit'),
        ];
    }

    protected static function getTitle(): string
    {
        return trans_choice('level', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }

    public static function getModelLabel(): string
    {
        return trans_choice('level', 1);
    }
}
