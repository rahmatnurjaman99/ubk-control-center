<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\Pages;

use App\Filament\Resources\SubjectCategories\SubjectCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubjectCategories extends ListRecords
{
    protected static string $resource = SubjectCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
