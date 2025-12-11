<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EducationLevel;
use App\Enums\StaffRole;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Staff>
 */
class StaffFactory extends Factory
{
    public function definition(): array
    {
        $joined = fake()->dateTimeBetween('-3 years', 'now');

        return [
            'user_id' => User::factory(),
            'staff_number' => fake()->unique()->regexify('STF-[A-Z0-9]{5}'),
            'staff_name' => fake()->name(),
            'role' => fake()->randomElement(StaffRole::cases()),
            'joined_on' => $joined->format('Y-m-d'),
            'phone' => fake()->phoneNumber(),
            'education_level' => fake()->randomElement(EducationLevel::cases()),
            'education_institution' => fake()->company(),
            'graduated_year' => (int) $joined->modify('-5 years')->format('Y'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
        ];
    }
}
