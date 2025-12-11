<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use App\Models\Schedule;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    private ?Carbon $repeatUntil = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->repeatUntil = isset($data['repeat_weekly_until'])
            ? Carbon::parse($data['repeat_weekly_until'])->endOfDay()
            : null;

        unset($data['repeat_weekly_until']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->repeatUntil === null) {
            return;
        }

        $record = $this->record;
        $startsAt = $record->starts_at?->copy();
        $endsAt = $record->ends_at?->copy();

        if (! $record instanceof Schedule || $startsAt === null || $endsAt === null) {
            return;
        }

        $nextStart = $startsAt->copy()->addWeek();
        $nextEnd = $endsAt->copy()->addWeek();
        $created = 0;

        while ($nextStart->lte($this->repeatUntil)) {
            $duplicate = $record->replicate();
            $duplicate->starts_at = $nextStart;
            $duplicate->ends_at = $nextEnd;

            try {
                $duplicate->save();
                $created++;
            } catch (ValidationException $exception) {
                Notification::make()
                    ->title(__('filament.schedules.notifications.recurring_conflict'))
                    ->body($exception->getMessage())
                    ->warning()
                    ->send();

                break;
            }

            $nextStart = $nextStart->copy()->addWeek();
            $nextEnd = $nextEnd->copy()->addWeek();
        }

        if ($created > 0) {
            Notification::make()
                ->title(__('filament.schedules.notifications.recurring_created', ['count' => $created]))
                ->success()
                ->send();
        }
    }
}
