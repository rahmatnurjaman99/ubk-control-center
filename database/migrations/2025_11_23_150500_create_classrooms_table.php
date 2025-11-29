<?php

declare(strict_types=1);

use App\Enums\SchoolLevel;
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
        Schema::create('classrooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('homeroom_staff_id')
                ->nullable()
                ->constrained('staff')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name')->index();
            $table->string('school_level', 20)
                ->default(SchoolLevel::Sd->value)
                ->index();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
