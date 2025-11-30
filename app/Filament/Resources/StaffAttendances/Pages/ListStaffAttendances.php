<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffAttendances\Pages;

use App\Enums\AttendanceStatus;
use App\Filament\Resources\StaffAttendances\StaffAttendanceResource;
use App\Models\Staff;
use App\Models\StaffAttendance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;

class ListStaffAttendances extends ListRecords
{
    protected static string $resource = StaffAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getGenerateRosterAction(),
            CreateAction::make(),
        ];
    }

    private function getGenerateRosterAction(): Action
    {
        return Action::make('generateRoster')
            ->label(__('filament.staff_attendances.actions.generate'))
            ->icon(Heroicon::OutlinedClipboardDocumentCheck)
            ->form([
                DatePicker::make('recorded_on')
                    ->label(__('filament.staff_attendances.fields.recorded_on'))
                    ->native(false)
                    ->default(now())
                    ->required(),
            ])
            ->action(function (array $data): void {
                $recordedOn = Carbon::parse($data['recorded_on'])->toDateString();

                $staffMembers = Staff::query()
                    ->orderBy('staff_name')
                    ->get();

                foreach ($staffMembers as $staff) {
                    StaffAttendance::query()->updateOrCreate(
                        [
                            'staff_id' => $staff->id,
                            'recorded_on' => $recordedOn,
                        ],
                        [
                            'status' => AttendanceStatus::Present,
                            'recorded_by' => auth()->id(),
                        ],
                    );
                }

                Notification::make()
                    ->title(__('filament.staff_attendances.actions.generate_success', ['count' => $staffMembers->count()]))
                    ->success()
                    ->send();
            });
    }
}
