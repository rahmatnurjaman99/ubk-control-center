<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillagesTables extends Migration
{
    public function up(): void
    {
        Schema::create('villages', function (Blueprint $table): void {
            $table->char('id', 10)->primary();
            $table->char('district_id', 7);
            $table->string('name', 50)->index();
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
}
