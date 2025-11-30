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
        Schema::create('fees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')
                ->nullable()
                ->constrained('students')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('title');
            $table->string('type', 30)->index();
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->date('due_date')->nullable()->index();
            $table->string('status', 20)->default('pending')->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
