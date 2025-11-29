<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StaffRole: string implements HasLabel, HasColor
{
    case Principal = 'principal';
    case VicePrincipal = 'vice_principal';
    case Administrator = 'administrator';
    case Teacher = 'teacher';
    case Counselor = 'counselor';
    case Accountant = 'accountant';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Principal => __('filament.staff.roles.principal'),
            self::VicePrincipal => __('filament.staff.roles.vice_principal'),
            self::Administrator => __('filament.staff.roles.administrator'),
            self::Teacher => __('filament.staff.roles.teacher'),
            self::Counselor => __('filament.staff.roles.counselor'),
            self::Accountant => __('filament.staff.roles.accountant'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Principal => 'warning',
            self::VicePrincipal => 'warning',
            self::Administrator => 'info',
            self::Teacher => 'success',
            self::Counselor => 'primary',
            self::Accountant => 'gray',
        };
    }
}
