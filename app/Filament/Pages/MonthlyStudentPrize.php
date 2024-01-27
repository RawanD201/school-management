<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Student;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use App\Filament\Resources\StudentResource;


class MonthlyStudentPrize extends ListRecords
{

    protected static string $resource = StudentResource::class;

    protected static ?string $navigationGroup = 'labels.nav.group.reports';

    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    protected static ?string $slug = 'reports/monthly-student-prize';


    public function getTableQuery(): Builder
    {

        $query = Student::withCount(['StudentPrize' => function ($q) {
            $q->whereMonth('created_at', '=', now()->month)
                ->whereYear('created_at', '=', now()->year);
        }]);

        return $query;
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }

    protected function getActions(): array
    {
        return [];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function getTableFilters(): array
    {
        return [];
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('index')
                ->grow(false)
                ->label(__('index'))
                ->rowIndex(),
            Tables\Columns\TextColumn::make('id')
                ->label(__('id'))
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('fullname')
                ->label(__('attr.fullname'))
                ->toggleable(),
            Tables\Columns\BadgeColumn::make('student_prize_count')
                ->label(__('attr.student_prize_count'))
                ->toggleable(),

        ];
    }

    protected function getTitle(): string
    {
        return  trans_choice('monthly_student_prize', 2);
    }

    protected static function getNavigationLabel(): string
    {
        return  trans_choice('monthly_student_prize', 2);
    }

    public  function getPluralModelLabel(): string
    {
        return  trans_choice('monthly_student_prize', 2);
    }


    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return null;
    }
}
