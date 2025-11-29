<?php

declare(strict_types=1);

namespace App\Enums;

enum GradeLevel: string
{
    case Paud = 'paud';
    case TkA = 'tk_a';
    case TkB = 'tk_b';
    case Sd1 = 'sd_1';
    case Sd2 = 'sd_2';
    case Sd3 = 'sd_3';
    case Sd4 = 'sd_4';
    case Sd5 = 'sd_5';
    case Sd6 = 'sd_6';

    public function label(): string
    {
        return match ($this) {
            self::Paud => __('filament.grade_levels.paud'),
            self::TkA => __('filament.grade_levels.tka'),
            self::TkB => __('filament.grade_levels.tkb'),
            self::Sd1 => __('filament.grade_levels.sd_1'),
            self::Sd2 => __('filament.grade_levels.sd_2'),
            self::Sd3 => __('filament.grade_levels.sd_3'),
            self::Sd4 => __('filament.grade_levels.sd_4'),
            self::Sd5 => __('filament.grade_levels.sd_5'),
            self::Sd6 => __('filament.grade_levels.sd_6'),
        };
    }

    public function schoolLevel(): SchoolLevel
    {
        return match ($this) {
            self::Paud => SchoolLevel::Paud,
            self::TkA, self::TkB => SchoolLevel::Tk,
            default => SchoolLevel::Sd,
        };
    }

    public function next(): ?self
    {
        return match ($this) {
            self::Paud, self::TkB, self::Sd6 => null,
            self::TkA => self::TkB,
            self::Sd1 => self::Sd2,
            self::Sd2 => self::Sd3,
            self::Sd3 => self::Sd4,
            self::Sd4 => self::Sd5,
            self::Sd5 => self::Sd6,
        };
    }

    public function isTerminal(): bool
    {
        return $this->next() === null;
    }

    /**
     * @return array<string, string>
     */
    public static function options(?SchoolLevel $level = null): array
    {
        $cases = array_filter(
            self::cases(),
            fn (self $grade): bool => $level === null || $grade->schoolLevel() === $level,
        );

        $options = [];

        foreach ($cases as $grade) {
            $options[$grade->value] = $grade->label();
        }

        return $options;
    }
}
