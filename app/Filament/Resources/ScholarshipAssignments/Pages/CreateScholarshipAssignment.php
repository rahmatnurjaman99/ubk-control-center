<?php

declare(strict_types=1);

namespace App\Filament\Resources\ScholarshipAssignments\Pages;

use App\Filament\Resources\ScholarshipAssignments\ScholarshipAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScholarshipAssignment extends CreateRecord
{
    protected static string $resource = ScholarshipAssignmentResource::class;
}
