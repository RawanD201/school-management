<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use App\Models\Expense;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ExpenseType;
use Filament\Resources\Form;
use Filament\Tables\Filters;
use Filament\Resources\Table;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Component;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExpenseResource\Pages;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Group::make()
                    ->schema([
                        Components\Card::make([
                            Components\TextInput::make('name')
                                ->label(__('attr.name'))
                                ->required()
                                ->maxLength(255),
                            Components\Select::make('user')
                                ->label(__('attr.user'))
                                ->options(
                                    fn () =>
                                    Student::select('fullname')
                                        ->union(Teacher::select('fullname'))
                                        ->union(User::select('fullname'))->pluck('fullname', 'fullname')
                                )
                                ->required(),
                            Components\TextInput::make('amount')
                                ->label(__('attr.amount'))
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->suffix('IQD'),
                            Components\DatePicker::make('date')
                                ->label(__('attr.date'))
                                ->firstDayOfWeek(6)
                                ->displayFormat('d/m/Y')
                                ->default(now()),
                        ])->columns(),
                    ])->columnSpan(['lg' => 2]),
                Components\Group::make()
                    ->schema([
                        Components\Section::make(trans_choice('expense_type', 1) . '*')
                            ->schema([
                                Components\Select::make('expense_type_id')
                                    ->label('')
                                    ->options(ExpenseType::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->getSearchResultsUsing(
                                        fn (string $search) => ExpenseType::where('name', 'LIKE', "%{$search}%")->pluck('name', 'id')
                                    )
                                    ->required()
                                    ->preload()
                                    ->createOptionForm([
                                        Components\TextInput::make('name')
                                            ->label(trans_choice('expense_type', 1))
                                            ->minLength(2)
                                            ->maxLength(255)
                                            ->required(),
                                    ])
                                    ->createOptionAction(function (Components\Actions\Action $action) {
                                        return $action
                                            ->modalHeading(__('create', ['label' => trans_choice('expense_type', 1)]))
                                            ->modalButton(__('create', ['label' => trans_choice('expense_type', 1)]))
                                            ->modalWidth('md');
                                    })
                                    ->createOptionUsing(function (array $data) {
                                        $record = ExpenseType::create($data);
                                        // Return key item for option
                                        return $record->id;
                                    })->prefixAction(
                                        fn (?string $state, \Closure $set, Component $component): Components\Actions\Action => Components\Actions\Action::make('edit-expense')
                                            ->icon('heroicon-o-pencil-alt')
                                            ->color($state ? 'primary' : 'secondary')
                                            ->disabled(!$state)
                                            ->mountUsing(fn (ComponentContainer $form) => $form->fill([
                                                'name' => ExpenseType::find($state)->name,
                                            ]))
                                            ->form([
                                                Components\TextInput::make('name')
                                                    ->label(trans_choice('expense', 1))
                                                    ->minLength(1)
                                                    ->maxLength(255)
                                                    ->required(),
                                            ])
                                            ->action(function (array $data) use ($state, $component): void {
                                                ExpenseType::find($state)->update($data);
                                                $component->state(null);
                                            })
                                    ),
                            ]),
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
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('id'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type.name')
                    ->label(__('attr.expense_type'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('attr.name'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('attr.amount'))
                    ->icon('heroicon-o-cash')
                    ->toggleable()
                    ->formatStateUsing(fn (Expense $record) => number_format($record->amount))
                    ->suffix('IQD')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->icon('heroicon-o-pencil')
                    ->label(__('created_at'))
                    ->toggleable()
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->icon('heroicon-o-refresh')
                    ->label(__('updated_at'))
                    ->dateTime('d/m/Y H:i:s')
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\Filter::make('type_name')
                    ->form([
                        Components\Select::make('names')
                            ->label(trans_choice('expense_type', 1))
                            ->options(ExpenseType::all()->pluck('name', 'id'))
                            ->searchable()
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['names'],
                                fn (Builder $query, $names): Builder => $query
                                    ->whereHas(
                                        'type',
                                        fn ($q) => $q->whereIn('id', $names)
                                    ),
                            );
                    }),
                Tables\Filters\Filter::make('date')
                    ->label(__('date'))
                    ->indicator(__('date'))
                    ->form([
                        Components\DatePicker::make('created_from')
                            ->label(__('filters.created_from')),
                        Components\DatePicker::make('created_until')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }

    protected static function getTitle(): string
    {
        return trans_choice('expense', 2);
    }

    protected static function getNavigationLabel(): string
    {
        return static::getTitle();
    }

    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }

    public static function getModelLabel(): string
    {
        return trans_choice('expense', 1);
    }
}
