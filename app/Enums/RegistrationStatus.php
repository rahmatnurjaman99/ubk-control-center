<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RegistrationStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case PaymentVerified = 'payment_verified';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => __('filament.registration_intakes.statuses.pending'),
            self::PaymentVerified => __('filament.registration_intakes.statuses.payment_verified'),
            self::Completed => __('filament.registration_intakes.statuses.completed'),
            self::Cancelled => __('filament.registration_intakes.statuses.cancelled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::PaymentVerified => 'info',
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
            ->mapWithKeys(
                static fn(self $status): array => [$status->value => $status->getLabel() ?? $status->value],
            )
            ->all();
    }
}
