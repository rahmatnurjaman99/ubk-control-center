<?php

declare(strict_types=1);

namespace App\Filament\Resources\Payrolls\Pages;

use App\Filament\Resources\Payrolls\PayrollResource;
use App\Support\Finance\PayrollProcessor;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPayroll extends EditRecord
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateItems')
                ->label(__('filament.payrolls.actions.generate_items'))
                ->icon(Heroicon::OutlinedPlay)
                ->color('primary')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->runPayroll();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    private function runPayroll(): void
    {
        /** @var PayrollProcessor $processor */
        $processor = app(PayrollProcessor::class);

        $items = $processor->generate($this->record);

        $this->record->refresh();

        $this->notify('success', __('filament.payrolls.notifications.generated', [
            'count' => $items->count(),
        ]));
    }
}
