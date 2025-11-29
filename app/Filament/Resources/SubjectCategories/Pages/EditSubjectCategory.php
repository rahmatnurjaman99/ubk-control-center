<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectCategories\Pages;

use App\Filament\Resources\SubjectCategories\SubjectCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubjectCategory extends EditRecord
{
    protected static string $resource = SubjectCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
