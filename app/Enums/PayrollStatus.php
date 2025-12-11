<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PayrollStatus: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Processing = 'processing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('filament.payrolls.statuses.draft'),
            self::Processing => __('filament.payrolls.statuses.processing'),
            self::Completed => __('filament.payrolls.statuses.completed'),
            self::Cancelled => __('filament.payrolls.statuses.cancelled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Processing => 'warning',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->getLabel() ?? $status->value])
            ->all();
    }
}
