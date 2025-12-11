<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\GradeLevel;
use App\Enums\RegistrationStatus;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\RegistrationIntake;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<RegistrationIntake>
 */
class RegistrationIntakeFactory extends Factory
{
    public function definition(): array
    {
        $form = fake()->unique()->regexify('REG-[A-Z0-9]{5}');
        $paymentDate = fake()->boolean(70) ? Carbon::parse(fake()->dateTimeBetween('-1 month', 'now')) : null;

        return [
            'form_number' => $form,
            'payment_reference' => 'PAY-' . Str::upper(Str::random(6)),
            'payment_method' => fake()->randomElement(['cash', 'bank_transfer', 'card']),
            'payment_amount' => fake()->numberBetween(500_000, 1_500_000),
            'payment_received_at' => $paymentDate?->toDateString(),
            'guardian_name' => fake()->name(),
            'guardian_phone' => fake()->phoneNumber(),
            'guardian_email' => fake()->safeEmail(),
            'guardian_address' => fake()->address(),
            'student_full_name' => fake()->name(),
            'student_date_of_birth' => fake()->dateTimeBetween('-8 years', '-4 years')->format('Y-m-d'),
            'student_gender' => fake()->randomElement(['male', 'female']),
            'target_grade_level' => fake()->randomElement(GradeLevel::cases()),
            'academic_year_id' => AcademicYear::factory(),
            'classroom_id' => Classroom::factory(),
            'student_id' => null,
            'processed_by' => User::factory(),
            'processed_at' => $paymentDate?->copy()->addDays(3),
            'status' => fake()->randomElement(RegistrationStatus::cases()),
            'notes' => fake()->boolean(40) ? fake()->sentence() : null,
        ];
    }
}
