<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EducationLevel: string implements HasLabel
{
    case HighSchool = 'high_school';
    case Diploma = 'diploma';
    case Bachelor = 'bachelor';
    case Master = 'master';
    case Doctorate = 'doctorate';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HighSchool => __('filament.staff.education_levels.high_school'),
            self::Diploma => __('filament.staff.education_levels.diploma'),
            self::Bachelor => __('filament.staff.education_levels.bachelor'),
            self::Master => __('filament.staff.education_levels.master'),
            self::Doctorate => __('filament.staff.education_levels.doctorate'),
            self::Other => __('filament.staff.education_levels.other'),
        };
    }
}
