<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\StudentPayment;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentPaymentResource\Pages;
use App\Filament\Resources\StudentPaymentResource\RelationManagers;

class StudentPaymentResource extends Resource
{
    protected static ?string $model = StudentPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make([
                            Forms\Components\Select::make('student')
                                ->label(__('attr.student'))
                                ->options(
                                    Student::all()->pluck('fullname', 'fullname')
                                )
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->label(__('attr.amount'))
                                ->numeric()
                                ->minValue(0)
                                ->required()
                                ->suffix('IQD'),
                            Forms\Components\DatePicker::make('date')
                                ->label(__('attr.date'))
                                ->firstDayOfWeek(6)
                                ->displayFormat('d/m/Y')
                                ->default(now()),
                        ])->columns(3),
                        Forms\Components\Card::make([
                            Forms\Components\MarkdownEditor::make('note')
                                ->label(__('attr.note'))
                                ->disableAllToolbarButtons(),
                        ]),
                    ])->columnSpan(['lg' => 3]),
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
                    ->sortable()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('attr.id'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student')
                    ->label(__('attr.student'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('attr.amount'))
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->formatStateUsing(fn (Model $record) => number_format($record->amount)),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('attr.date'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('attr.note'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('student')
                    ->label(__('attr.student'))
                    ->searchable()
                    ->options(fn () => Student::all()->pluck('fullname', 'fullname'))
                    ->query(function (Builder $query, $data): Builder {
                        if (!\array_key_exists('value', $data) || blank($data['value'])) {
                            return $query;
                        }
                        return $query->where('student', $data['value']);
                    }),
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
            'index' => Pages\ListStudentPayments::route('/'),
            'create' => Pages\CreateStudentPayment::route('/create'),
            'edit' => Pages\EditStudentPayment::route('/{record}/edit'),
        ];
    }

    protected static function getTitle(): string
    {
        return trans_choice('student_payment', 2);
    }
    public static function getPluralModelLabel(): string
    {
        return static::getTitle();
    }
}
