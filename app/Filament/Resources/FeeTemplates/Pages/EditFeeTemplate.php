<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeeTemplates\Pages;

use App\Filament\Resources\FeeTemplates\FeeTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeeTemplate extends EditRecord
{
    protected static string $resource = FeeTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
