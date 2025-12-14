<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments\Pages;

use App\Filament\Resources\ScholarshipAssignments\ScholarshipAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScholarshipAssignments extends ListRecords
{
    protected static string $resource = ScholarshipAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
