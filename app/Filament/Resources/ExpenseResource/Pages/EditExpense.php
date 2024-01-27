<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Filament\Resources\ExpenseResource\Widgets\TotalExpense;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('labels.notify.edit', ['label' => trans_choice('expense', 1)]);
    }

    protected function getTitle(): string
    {
        return trans_choice('expense', 1);
    }

    public function getModelLabel(): string
    {
        return trans_choice('expense', 1);
    }

    public function getFormTabLabel(): ?string
    {
        return __('edit', ['label' => trans_choice('expense', 1)]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction()
                ->url(ExpenseResource::getUrl('index')),
        ];
    }
}
