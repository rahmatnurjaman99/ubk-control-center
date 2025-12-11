<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTables extends Migration
{
    public function up(): void
    {
        Schema::create('districts', function (Blueprint $table): void {
            $table->char('id', 7)->primary();
            $table->char('regency_id', 4);
            $table->string('name', 50)->index();
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
}
