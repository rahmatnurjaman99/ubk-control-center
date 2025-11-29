<?php

declare(strict_types=1);

namespace App\Enums;

enum SchoolLevel: string
{
    case Paud = 'paud';
    case Tk = 'tk';
    case Sd = 'sd';

    public function label(): string
    {
        return match ($this) {
            self::Paud => __('filament.school_levels.paud'),
            self::Tk => __('filament.school_levels.tk'),
            self::Sd => __('filament.school_levels.sd'),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $level) {
            $options[$level->value] = $level->label();
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    public function gradeOptions(): array
    {
        return GradeLevel::options($this);
    }
}
