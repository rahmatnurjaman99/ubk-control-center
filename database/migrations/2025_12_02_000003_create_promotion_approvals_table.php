<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotion_approvals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('current_academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('target_academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('target_classroom_id')
                ->nullable()
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('target_grade_level', 50)->nullable();
            $table->decimal('outstanding_amount', 16, 2)->default(0);
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('decision_notes')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_approvals');
    }
};
