<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::parse(fake()->dateTimeBetween('-2 years', '+1 year'));
        $end = (clone $start)->addYear();
        $code = sprintf('%s-%s', $start->format('Y'), $end->format('Y'));

        return [
            'code' => $code,
            'name' => 'Academic Year ' . $code,
            'starts_on' => $start->toDateString(),
            'ends_on' => $end->toDateString(),
            'is_current' => false,
        ];
    }
}
