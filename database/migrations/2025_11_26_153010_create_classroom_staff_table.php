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
        Schema::create('classroom_staff', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('classroom_id')
                ->constrained('classrooms')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('staff_id')
                ->constrained('staff')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('assignment_role', 40)->index();
            $table->date('assigned_on')->nullable()->index();
            $table->date('removed_on')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index(['classroom_id', 'assignment_role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_staff');
    }
};
