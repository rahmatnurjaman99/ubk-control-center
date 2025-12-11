<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'guardian_number' => fake()->unique()->regexify('GRD-[A-Z0-9]{6}'),
            'full_name' => fake()->name(),
            'relationship' => fake()->randomElement(['parent', 'uncle', 'aunt', 'guardian']),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'occupation' => fake()->jobTitle(),
            'address' => fake()->address(),
            'legacy_reference' => 'LEG-GRD-' . fake()->unique()->numerify('#####'),
        ];
    }
}
