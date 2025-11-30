<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AttendanceStatus: string implements HasLabel, HasColor
{
    case Present = 'present';
    case Absent = 'absent';
    case Late = 'late';
    case Excused = 'excused';
    case Sick = 'sick';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Present => __('filament.attendance.statuses.present'),
            self::Absent => __('filament.attendance.statuses.absent'),
            self::Late => __('filament.attendance.statuses.late'),
            self::Excused => __('filament.attendance.statuses.excused'),
            self::Sick => __('filament.attendance.statuses.sick'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Present => 'success',
            self::Absent => 'danger',
            self::Late => 'warning',
            self::Excused => 'info',
            self::Sick => 'gray',
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
