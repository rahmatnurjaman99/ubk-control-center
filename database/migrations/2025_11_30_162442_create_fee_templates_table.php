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
        Schema::create('fee_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('grade_level', 30)->index();
            $table->string('type', 30)->default('tuition');
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->unsignedSmallInteger('due_in_days')->default(14);
            $table->boolean('is_active')->default(true)->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index(['grade_level', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_templates');
    }
};
