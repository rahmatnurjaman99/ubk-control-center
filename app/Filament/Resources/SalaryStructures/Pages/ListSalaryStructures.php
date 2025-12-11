<?php

declare(strict_types=1);

namespace App\Filament\Resources\SalaryStructures\Pages;

use App\Filament\Resources\SalaryStructures\SalaryStructureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaryStructures extends ListRecords
{
    protected static string $resource = SalaryStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
