<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FeeType: string implements HasLabel, HasColor
{
    case Tuition = 'tuition';
    case Registration = 'registration';
    case Uniform = 'uniform';
    case Misc = 'misc';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Tuition => __('filament.fees.types.tuition'),
            self::Registration => __('filament.fees.types.registration'),
            self::Uniform => __('filament.fees.types.uniform'),
            self::Misc => __('filament.fees.types.misc'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Tuition => 'primary',
            self::Registration => 'info',
            self::Uniform => 'warning',
            self::Misc => 'gray',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $type): array => [$type->value => $type->getLabel() ?? $type->value])
            ->all();
    }
}
