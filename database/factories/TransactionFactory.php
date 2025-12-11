<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Models\AcademicYear;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        $due = Carbon::parse(fake()->dateTimeBetween('-1 month', '+1 month'));
        $paid = fake()->boolean(60) ? $due->copy()->addDays(fake()->numberBetween(0, 3)) : null;
        $paymentStatus = $paid
            ? PaymentStatus::Paid
            : fake()->randomElement([
                PaymentStatus::Pending,
                PaymentStatus::Partial,
                PaymentStatus::Cancelled,
            ]);

        return [
            'reference' => Transaction::generateReference(),
            'label' => ucfirst(fake()->words(3, true)),
            'type' => fake()->randomElement(TransactionType::cases()),
            'category' => fake()->randomElement(['tuition', 'registration', 'supplies', 'salary']),
            'payment_status' => $paymentStatus,
            'payment_method' => fake()->randomElement(['cash', 'transfer', 'card']),
            'amount' => fake()->numberBetween(100_000, 2_000_000),
            'currency' => 'IDR',
            'due_date' => $due->toDateString(),
            'paid_at' => $paid,
            'academic_year_id' => AcademicYear::factory(),
            'counterparty_name' => fake()->company(),
            'recorded_by' => User::factory(),
            'notes' => fake()->sentence(),
            'metadata' => [
                'invoice' => fake()->unique()->numerify('INV-#####'),
            ],
        ];
    }
}
