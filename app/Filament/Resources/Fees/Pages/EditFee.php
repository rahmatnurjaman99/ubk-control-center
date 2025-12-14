<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Pages;

use App\Filament\Resources\Fees\FeeResource;
use App\Filament\Resources\Fees\Schemas\FeeForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFee extends EditRecord
{
    protected static string $resource = FeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return FeeForm::mutateSubmissionData($data, $this->record);
    }
}
