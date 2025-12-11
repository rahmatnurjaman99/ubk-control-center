<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Widgets;

use App\Models\Schedule;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class SchedulesCalendarWidget extends CalendarWidget
{
    protected HtmlString | string | bool | null $heading = 'Calendar';

    protected function getEvents(FetchInfo $info): Collection
    {
        return Schedule::query()
            ->visibleTo(auth()->user())
            ->with(['subject', 'classroom', 'teacher'])
            ->where('starts_at', '<', $info->end)
            ->where('ends_at', '>', $info->start)
            ->get()
            ->map(fn (Schedule $schedule) => $schedule->toCalendarEvent());
    }
}
