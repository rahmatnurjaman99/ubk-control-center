<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\Pages;

use App\Filament\Resources\SubjectCategories\SubjectCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubjectCategory extends CreateRecord
{
    protected static string $resource = SubjectCategoryResource::class;
}
