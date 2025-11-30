<?php

declare(strict_types=1);

namespace App\Filament\Resources\FeeTemplates\Pages;

use App\Filament\Resources\FeeTemplates\FeeTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeTemplate extends CreateRecord
{
    protected static string $resource = FeeTemplateResource::class;
}
