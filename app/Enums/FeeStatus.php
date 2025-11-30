<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FeeStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Partial = 'partial';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('filament.fees.statuses.pending'),
            self::Partial => __('filament.fees.statuses.partial'),
            self::Paid => __('filament.fees.statuses.paid'),
            self::Cancelled => __('filament.fees.statuses.cancelled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Partial => 'info',
            self::Paid => 'success',
            self::Cancelled => 'gray',
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
