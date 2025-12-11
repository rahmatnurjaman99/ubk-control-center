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
        Schema::create('payrolls', function (Blueprint $table): void {
            $table->id();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('status', 20)->default('draft')->index();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->date('period_start')->index();
            $table->date('period_end')->index();
            $table->timestamp('processed_at')->nullable()->index();
            $table->decimal('total_base_salary', 16, 2)->default(0);
            $table->decimal('total_allowances', 16, 2)->default(0);
            $table->decimal('total_deductions', 16, 2)->default(0);
            $table->decimal('total_net', 16, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->json('staff_ids')->nullable();
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
        Schema::dropIfExists('payrolls');
    }
};
