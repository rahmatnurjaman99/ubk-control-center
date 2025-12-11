<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PayrollItemStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Paid = 'paid';
    case OnHold = 'on_hold';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('filament.payroll_items.statuses.pending'),
            self::Approved => __('filament.payroll_items.statuses.approved'),
            self::Paid => __('filament.payroll_items.statuses.paid'),
            self::OnHold => __('filament.payroll_items.statuses.on_hold'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'info',
            self::Paid => 'success',
            self::OnHold => 'gray',
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
