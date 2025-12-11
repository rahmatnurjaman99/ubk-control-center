<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegenciesTables extends Migration
{
    public function up(): void
    {
        Schema::create('regencies', function (Blueprint $table): void {
            $table->char('id', 4)->primary();
            $table->char('province_id', 2);
            $table->string('name', 50)->index();
            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
}
