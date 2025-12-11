<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use App\Filament\Resources\Schedules\Widgets\SchedulesCalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        if (! ScheduleResource::canCreate()) {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SchedulesCalendarWidget::class,
        ];
    }
}
