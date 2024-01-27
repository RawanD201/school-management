<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use AlperenErsoy\FilamentExport\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\TextInput\Mask;
use App\Filament\Resources\UserResource\Pages;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'labels.nav.group.settings';

    protected static ?string $slug = 'users';

    protected static ?int $navigationSort = -1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('fullname')
                            ->label(__('attr.fullname'))
                            ->required()
                            ->minLength(2)
                            ->maxLength(255),
                        TextInput::make('username')
                            ->label(__('attr.username'))
                            ->minLength(2)
                            ->maxLength(255)
                            ->required(),
                        DatePicker::make('birthday')
                            ->label(__('attr.birthday'))
                            ->default(now())
                            ->displayFormat('d/m/Y')
                            ->required(),
                        TextInput::make('phone')
                            ->label(__('attr.phone'))
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->mask(fn (Mask $mask) => $mask->pattern('00000000000'))
                            ->autocomplete(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label(__('attr.index'))
                    ->toggleable()
                    ->grow(false)
                    ->getStateUsing(
                        static fn (\stdClass $rowLoop): string => (string) $rowLoop->iteration
                    )
                    ->rowIndex(),
                TextColumn::make('fullname')
                    ->label(__('attr.name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('username')
                    ->label(__('attr.username'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label(__('attr.status'))
                    ->grow(false)
                    ->options([
                        'heroicon-o-check-circle' => __('true'),
                        'heroicon-o-x-circle' => __('false'),
                    ])
                    ->colors([
                        'success' => __('true'),
                        'danger' => __('false'),
                    ])
                    ->getStateUsing(fn (User $record) => $record->is_active ? __('true') : __('false'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('attr.phone'))
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('birthday')
                    ->label(__('attr.birthday'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('attr.created_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('attr.updated_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
            ])->defaultSort('id', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('attr.status'))
                    ->indicator('Actives')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\FilamentExportBulkAction::make('export')
                    ->label(__('actions.export'))
                    ->extraViewData([
                        'getPageHeader' => fn () => static::getTitle(),
                    ])
                    ->disablePdf(),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getTitle(): string
    {
        return trans_choice('user', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }
}
