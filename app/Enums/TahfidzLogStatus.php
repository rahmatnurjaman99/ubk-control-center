<?php

declare(strict_types=1);

namespace App\Enums;

enum TahfidzLogStatus: string
{
    case Pending = 'pending';
    case Passed = 'passed';
    case NeedsRevision = 'needs_revision';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Passed => 'Passed',
            self::NeedsRevision => 'Needs Revision',
            self::Failed => 'Failed',
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
