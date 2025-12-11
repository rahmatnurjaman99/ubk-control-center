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
        Schema::table('staff', function (Blueprint $table): void {
            $table->string('bank_name', 100)
                ->nullable()
                ->after('phone')
                ->index();
            $table->string('bank_account_name')
                ->nullable()
                ->after('bank_name')
                ->index();
            $table->string('bank_account_number', 50)
                ->nullable()
                ->after('bank_account_name')
                ->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table): void {
            $table->dropUnique(['bank_account_number']);
            $table->dropColumn([
                'bank_name',
                'bank_account_name',
                'bank_account_number',
            ]);
        });
    }
};
