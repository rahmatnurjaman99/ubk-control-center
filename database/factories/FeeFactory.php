<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FeeStatus;
use App\Enums\FeeType;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\Student;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Fee>
 */
class FeeFactory extends Factory
{
    public function definition(): array
    {
        $due = Carbon::parse(fake()->dateTimeBetween('-1 month', '+2 months'));
        $amount = fake()->numberBetween(500_000, 2_500_000);
        $status = fake()->randomElement(FeeStatus::cases());

        $paidAmount = 0;
        $paidAt = null;

        if ($status === FeeStatus::Paid) {
            $paidAmount = $amount;
            $paidAt = (clone $due)->addDays(fake()->numberBetween(0, 5));
        } elseif ($status === FeeStatus::Partial) {
            $partialMax = max($amount - 1, 1);
            $paidAmount = fake()->numberBetween(1, (int) $partialMax);
        }

        return [
            'reference' => Fee::generateReference(),
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'transaction_id' => Transaction::factory(),
            'title' => ucfirst(fake()->words(3, true)),
            'type' => fake()->randomElement(FeeType::cases()),
            'amount' => $amount,
            'paid_amount' => $paidAmount,
            'currency' => 'IDR',
            'due_date' => $due->format('Y-m-d'),
            'status' => $status,
            'paid_at' => $paidAt,
            'description' => fake()->sentence(),
            'metadata' => [
                'issued_by' => fake()->name(),
            ],
        ];
    }
}
