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
        Schema::create('fee_partial_approvals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('fee_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('guardian_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('status')->default('pending')->index();
            $table->foreignId('scholarship_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->text('reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_partial_approvals');
    }
};
