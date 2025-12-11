<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SubjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SubjectCategory>
 */
class SubjectCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->word());

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
