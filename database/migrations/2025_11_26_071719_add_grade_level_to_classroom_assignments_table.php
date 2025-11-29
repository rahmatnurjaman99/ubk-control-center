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
        Schema::table('classroom_assignments', function (Blueprint $table): void {
            $table->string('grade_level', 20)
                ->nullable()
                ->after('classroom_id')
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classroom_assignments', function (Blueprint $table): void {
            $table->dropColumn('grade_level');
        });
    }
};
