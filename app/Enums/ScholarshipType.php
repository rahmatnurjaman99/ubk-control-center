<?php

declare(strict_types=1);

namespace App\Enums;

enum ScholarshipType: string
{
    case Percentage = 'percentage';
    case Nominal = 'nominal';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => __('filament.scholarships.types.percentage'),
            self::Nominal => __('filament.scholarships.types.nominal'),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
