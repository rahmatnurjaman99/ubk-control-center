<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Pages;

use App\Filament\Resources\RegistrationIntakes\RegistrationIntakeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationIntakes extends ListRecords
{
    protected static string $resource = RegistrationIntakeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
