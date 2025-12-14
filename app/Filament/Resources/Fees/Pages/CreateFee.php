<?php

declare(strict_types=1);

namespace App\Filament\Resources\Fees\Pages;

use App\Filament\Resources\Fees\FeeResource;
use App\Filament\Resources\Fees\Schemas\FeeForm;
use Filament\Resources\Pages\CreateRecord;

class CreateFee extends CreateRecord
{
    protected static string $resource = FeeResource::class;

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return FeeForm::mutateSubmissionData($data);
    }
}
