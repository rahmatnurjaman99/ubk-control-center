<?php

declare(strict_types=1);

namespace App\Enums;

enum TahfidzTargetStatus: string
{
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Missed = 'missed';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Assigned => 'Assigned',
            self::InProgress => 'In Progress',
            self::Completed => 'Completed',
            self::Missed => 'Missed',
            self::OnHold => 'On Hold',
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
