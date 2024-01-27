<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExpenseResource;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Widgets\TotalExpense as WidgetsTotalExpense;
use App\Filament\Resources\ExpenseResource\Widgets\TotalExpense;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class ListExpenses extends ListRecords
{
    protected static string $resource = ExpenseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('actions.add', ['label' => trans_choice('expense', 1)])),
        ];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('labels.notify.add', ['label' => trans_choice('expense', 1)]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('labels.notify.edit', ['label' => trans_choice('expense', 1)]);
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        return $query;
    }

    protected function getTitle(): string
    {
        return trans_choice('expense', 2);
    }

    public function getModelLabel(): string
    {
        return trans_choice('expense', 1);
    }

    public function getTableBulkActions(): array
    {
        return [
            FilamentExportBulkAction::make('export')
                ->label(__('actions.export'))
                ->extraViewData([
                    'data' => [
                        'getPageHeader' => fn () => static::getTitle(),
                    ]
                ])
                ->disablePdf(),
            DeleteBulkAction::make()
                ->label(__('actions.delete', ['label' => trans_choice('expense', 1)])),
        ];
    }
}
