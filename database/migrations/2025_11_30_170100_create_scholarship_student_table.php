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
        Schema::create('scholarship_student', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('scholarship_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['scholarship_id', 'student_id', 'effective_from'], 'scholarship_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_student');
    }
};
