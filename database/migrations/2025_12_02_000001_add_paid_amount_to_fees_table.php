<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fees', function (Blueprint $table): void {
            $table->decimal('paid_amount', 16, 2)
                ->default(0)
                ->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table): void {
            $table->dropColumn('paid_amount');
        });
    }
};
