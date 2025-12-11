<?php

namespace App\Filament\Resources\TahfidzTargets\Pages;

use App\Filament\Resources\TahfidzTargets\TahfidzTargetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTahfidzTarget extends EditRecord
{
    protected static string $resource = TahfidzTargetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
