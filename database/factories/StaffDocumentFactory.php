<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StaffDocument>
 */
class StaffDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'staff_id' => Staff::factory(),
            'name' => ucfirst(fake()->word()) . ' Certificate',
            'type' => fake()->randomElement(['contract', 'certificate', 'id_card']),
            'file_path' => 'staff-documents/' . fake()->uuid() . '.pdf',
            'notes' => fake()->sentence(),
        ];
    }
}
