<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PromotionApprovalStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('filament.promotion_approvals.statuses.pending'),
            self::Approved => __('filament.promotion_approvals.statuses.approved'),
            self::Rejected => __('filament.promotion_approvals.statuses.rejected'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $status): array => [$status->value => $status->getLabel() ?? $status->value])
            ->all();
    }
}
