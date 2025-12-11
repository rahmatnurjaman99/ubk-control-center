<?php

declare(strict_types=1);

namespace App\Filament\Resources\PromotionApprovalResource\Pages;

use App\Filament\Resources\PromotionApprovalResource;
use Filament\Resources\Pages\ListRecords;

class ListPromotionApprovals extends ListRecords
{
    protected static string $resource = PromotionApprovalResource::class;
}
