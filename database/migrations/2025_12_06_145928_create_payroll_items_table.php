<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payroll_id')
                ->constrained('payrolls')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('salary_structure_id')
                ->nullable()
                ->constrained('salary_structures')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('status', 20)->default('pending')->index();
            $table->decimal('base_salary', 16, 2)->default(0);
            $table->json('allowances')->nullable();
            $table->decimal('allowances_total', 16, 2)->default(0);
            $table->json('deductions')->nullable();
            $table->decimal('deductions_total', 16, 2)->default(0);
            $table->decimal('net_amount', 16, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['payroll_id', 'staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
