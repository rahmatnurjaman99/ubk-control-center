<?php

namespace App\Filament\Resources\TahfidzTargets\Pages;

use App\Filament\Resources\TahfidzTargets\TahfidzTargetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTahfidzTargets extends ListRecords
{
    protected static string $resource = TahfidzTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
