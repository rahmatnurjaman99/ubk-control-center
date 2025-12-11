<?php

declare(strict_types=1);

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return parent::handleRecordUpdate($record, $data);
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())
                ->flatten()
                ->filter()
                ->implode(PHP_EOL);

            Notification::make()
                ->title(__('filament.schedules.notifications.save_failed'))
                ->body($message !== '' ? $message : $exception->getMessage())
                ->danger()
                ->persistent()
                ->send();

            throw $exception;
        }
    }
}
