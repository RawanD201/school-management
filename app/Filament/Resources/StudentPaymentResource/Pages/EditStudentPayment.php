<?php

namespace App\Filament\Resources\StudentPaymentResource\Pages;

use App\Filament\Resources\StudentPaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentPayment extends EditRecord
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getTitle(): string
    {
        return trans_choice('student_payment', 1);
    }

    public function getModelLabel(): string
    {
        return trans_choice('student_payment', 1);
    }
}
