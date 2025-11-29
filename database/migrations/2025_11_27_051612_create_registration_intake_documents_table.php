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
        Schema::create('registration_intake_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('registration_intake_id')
                ->constrained('registration_intakes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('name')->index();
            $table->string('type')->nullable()->index();
            $table->string('file_path');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_intake_documents');
    }
};
