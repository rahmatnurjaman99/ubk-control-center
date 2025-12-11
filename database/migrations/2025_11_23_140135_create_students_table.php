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
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('guardian_id')
                ->nullable()
                ->constrained('guardians')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->unique();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('student_number')->unique();
            $table->string('full_name')->index();
            $table->date('date_of_birth')->nullable()->index();
            $table->string('gender', 10)->nullable()->index();
            $table->text('address')->nullable();
            $table->char('province_id', 2)->nullable()->index();
            $table->char('regency_id', 4)->nullable()->index();
            $table->char('district_id', 7)->nullable()->index();
            $table->char('village_id', 10)->nullable()->index();
            $table->string('status', 30)->default('active')->index();
            $table->date('enrolled_on')->nullable()->index();
            $table->string('legacy_reference')->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->softDeletes()->index();

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
