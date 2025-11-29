<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StudentStatus: string implements HasLabel, HasColor
{
    case Active = 'active';
    case Graduated = 'graduated';
    case Transferred = 'transferred';
    case Inactive = 'inactive';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => __('filament.students.statuses.active'),
            self::Graduated => __('filament.students.statuses.graduated'),
            self::Transferred => __('filament.students.statuses.transferred'),
            self::Inactive => __('filament.students.statuses.inactive'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Graduated => 'primary',
            self::Transferred => 'warning',
            self::Inactive => 'gray',
        };
    }
}
