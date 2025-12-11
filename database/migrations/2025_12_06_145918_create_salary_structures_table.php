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
        Schema::create('salary_structures', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('title');
            $table->string('currency', 3)->default('IDR');
            $table->decimal('base_salary', 16, 2)->default(0);
            $table->json('allowances')->nullable();
            $table->decimal('allowances_total', 16, 2)->default(0);
            $table->json('deductions')->nullable();
            $table->decimal('deductions_total', 16, 2)->default(0);
            $table->date('effective_date')->index();
            $table->date('expires_on')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
