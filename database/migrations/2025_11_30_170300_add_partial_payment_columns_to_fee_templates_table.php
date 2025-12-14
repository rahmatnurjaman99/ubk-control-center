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
        Schema::table('fee_templates', function (Blueprint $table): void {
            $table->boolean('allow_partial_payment')->default(false)->after('due_in_days');
            $table->boolean('require_partial_approval')->default(false)->after('allow_partial_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_templates', function (Blueprint $table): void {
            $table->dropColumn([
                'allow_partial_payment',
                'require_partial_approval',
            ]);
        });
    }
};
