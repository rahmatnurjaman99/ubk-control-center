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
        Schema::create('schedules', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->index();
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('classroom_id')
                ->nullable()
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->index();
            $table->boolean('is_all_day')->default(false);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['classroom_id', 'starts_at', 'ends_at']);
            $table->index(['staff_id', 'starts_at', 'ends_at']);
            $table->index(['subject_id']);
            $table->index(['classroom_id']);
            $table->index(['staff_id']);
            $table->index(['academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
