<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz_targets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('classroom_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('assigned_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->unsignedSmallInteger('surah_id')->index();
            $table->unsignedInteger('start_ayah_number');
            $table->unsignedInteger('end_ayah_number');
            $table->unsignedTinyInteger('target_repetitions')->default(1);
            $table->date('assigned_on')->nullable();
            $table->date('due_on')->nullable();
            $table->string('status', 32)->default('assigned');
            $table->string('tag')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_targets');
    }
};
