<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\AcademicYear;

class AcademicYearResolver
{
    private static ?int $cachedId = null;

    public static function currentId(): ?int
    {
        if (self::$cachedId !== null) {
            return self::$cachedId;
        }

        $current = AcademicYear::query()
            ->where('is_current', true)
            ->value('id');

        if ($current !== null) {
            return self::$cachedId = $current;
        }

        return self::$cachedId = AcademicYear::query()
            ->orderByDesc('starts_on')
            ->value('id');
    }
}
