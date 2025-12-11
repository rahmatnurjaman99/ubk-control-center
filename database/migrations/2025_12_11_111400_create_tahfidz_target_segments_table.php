<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahfidz_target_segments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tahfidz_target_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('surah_id')->index();
            $table->unsignedInteger('start_ayah_number');
            $table->unsignedInteger('end_ayah_number');
            $table->unsignedInteger('sequence')->default(1);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahfidz_target_segments');
    }
};
