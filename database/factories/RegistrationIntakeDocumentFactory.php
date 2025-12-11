<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RegistrationIntake;
use App\Models\RegistrationIntakeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RegistrationIntakeDocument>
 */
class RegistrationIntakeDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'registration_intake_id' => RegistrationIntake::factory(),
            'name' => ucfirst(fake()->word()) . ' Document',
            'type' => fake()->randomElement(['birth_certificate', 'family_card', 'photo']),
            'file_path' => 'documents/' . fake()->uuid() . '.pdf',
            'notes' => fake()->sentence(),
        ];
    }
}
