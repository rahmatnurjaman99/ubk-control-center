<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tahfidz_target_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('recorded_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('evaluated_by_staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();
            $table->unsignedSmallInteger('surah_id')->index();
            $table->unsignedInteger('start_ayah_number');
            $table->unsignedInteger('end_ayah_number');
            $table->date('recorded_on');
            $table->string('status', 32)->default('pending');
            $table->unsignedTinyInteger('memorization_score')->nullable();
            $table->unsignedTinyInteger('tajwid_score')->nullable();
            $table->unsignedTinyInteger('fluency_score')->nullable();
            $table->boolean('is_revision')->default(false);
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_logs');
    }
};
