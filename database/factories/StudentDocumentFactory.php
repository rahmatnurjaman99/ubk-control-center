<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\StudentDocument>
 */
class StudentDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'name' => fake()->sentence(3),
            'type' => fake()->randomElement(['birth_certificate', 'report_card', 'id_card']),
            'file_path' => 'student-documents/' . fake()->uuid() . '.pdf',
            'notes' => fake()->sentence(),
        ];
    }
}
