<?php

namespace App\Filament\Pages;

use Filament\Tables;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;

class MonthlyBestTeacher extends ListRecords
{

    protected static string $resource = TeacherResource::class;

    protected static ?string $navigationGroup = 'labels.nav.group.reports';

    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    protected static ?string $slug = 'reports/monthly-best-teacher';


    public function getTableQuery(): Builder
    {

        $query = Teacher::withCount(['TeacherActivity' => function ($q) {
            $q->whereMonth('created_at', '=', now()->month);
        }])->withCount(['TeacherPrize' => function ($q) {
            $q->whereMonth('created_at', '=', now()->month);
        }])->orderBy('teacher_activity_count', 'desc')
            ->orderBy('teacher_prize_count', 'desc');

        return $query;
    }

    protected function getTableBulkActions(): array
    {
        return [];
    }


    protected function getTableActions(): array
    {
        return [];
    }

    protected function getActions(): array
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
            Tables\Columns\BadgeColumn::make('teacher_prize_count')
                ->label(__('attr.teacher_prize_count'))
                ->toggleable(),
            Tables\Columns\BadgeColumn::make('teacher_activity_count')
                ->label(__('attr.teacher_activity_count'))
                ->toggleable(),
        ];
    }

    protected function getTitle(): string
    {
        return  trans_choice('monthly_best_teacher', 2);
    }

    protected static function getNavigationLabel(): string
    {
        return  trans_choice('monthly_best_teacher', 2);
    }

    public  function getPluralModelLabel(): string
    {
        return  trans_choice('monthly_best_teacher', 2);
    }


    protected function getTableRecordUrlUsing(): ?\Closure
    {
        return null;
    }
}
