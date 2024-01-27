<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('labels.notify.create', ['label' => trans_choice('expense', 1)]);
    }

    protected function getTitle(): string
    {
        return __('create', ['label' => trans_choice('expense', 1)]);
    }

    public function getModelLabel(): string
    {
        return __('create', ['label' => trans_choice('expense', 1)]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
