<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments\Pages;

use App\Filament\Resources\ScholarshipAssignments\ScholarshipAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScholarshipAssignment extends EditRecord
{
    protected static string $resource = ScholarshipAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
