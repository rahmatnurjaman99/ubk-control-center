<?php

declare(strict_types=1);

namespace App\Filament\Resources\RegistrationIntakes\Pages;

use App\Filament\Resources\RegistrationIntakes\RegistrationIntakeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistrationIntake extends CreateRecord
{
    protected static string $resource = RegistrationIntakeResource::class;
}
