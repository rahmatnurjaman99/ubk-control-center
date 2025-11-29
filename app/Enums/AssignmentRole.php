<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts.HasLabel;

enum AssignmentRole: string implements HasLabel, HasColor
{
    case Homeroom = 'homeroom';
    case SubjectTeacher = 'subject_teacher';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Homeroom => __('filament.assignment_roles.homeroom'),
            self::SubjectTeacher => __('filament.assignment_roles.subject_teacher'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Homeroom => 'primary',
            self::SubjectTeacher => 'success',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel() ?? $case->name;
        }

        return $options;
    }
}
