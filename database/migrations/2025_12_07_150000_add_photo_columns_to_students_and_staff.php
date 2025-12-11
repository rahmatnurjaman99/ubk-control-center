<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->string('photo_path')
                ->nullable()
                ->after('full_name');
        });

        Schema::table('staff', function (Blueprint $table): void {
            $table->string('photo_path')
                ->nullable()
                ->after('staff_name');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropColumn('photo_path');
        });

        Schema::table('staff', function (Blueprint $table): void {
            $table->dropColumn('photo_path');
        });
    }
};
