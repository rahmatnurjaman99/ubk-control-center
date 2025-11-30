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
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->string('reference')->unique();
            $table->string('label');
            $table->string('type', 20)->index();
            $table->string('category')->nullable()->index();
            $table->string('payment_status', 20)->default('pending')->index();
            $table->string('payment_method')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            $table->date('due_date')->nullable()->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->nullableMorphs('source');
            $table->string('counterparty_name')->nullable();
            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
