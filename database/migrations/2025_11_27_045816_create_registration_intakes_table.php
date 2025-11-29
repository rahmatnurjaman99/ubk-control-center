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
        Schema::create('registration_intakes', function (Blueprint $table): void {
            $table->id();
            $table->string('form_number')->unique();
            $table->string('payment_reference')->nullable()->index();
            $table->string('payment_method', 50)->nullable();
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->date('payment_received_at')->nullable()->index();
            $table->string('guardian_name');
            $table->string('guardian_phone')->index();
            $table->string('guardian_email')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('student_full_name');
            $table->date('student_date_of_birth')->nullable();
            $table->string('student_gender', 20)->nullable();
            $table->string('target_grade_level', 20)->nullable()->index();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('classroom_id')
                ->nullable()
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->string('status', 40)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_intakes');
    }
};
