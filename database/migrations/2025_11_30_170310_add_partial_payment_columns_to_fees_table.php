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
        Schema::table('fees', function (Blueprint $table): void {
            $table->boolean('allow_partial_payment')->default(false)->after('status');
            $table->boolean('requires_partial_approval')->default(false)->after('allow_partial_payment');
            $table->timestamp('partial_payment_approved_at')->nullable()->after('requires_partial_approval');
            $table->foreignId('partial_payment_approved_by')
                ->nullable()
                ->after('partial_payment_approved_at')
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('scholarship_id')
                ->nullable()
                ->after('partial_payment_approved_by')
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('scholarship_discount_amount', 12, 2)->default(0)->after('scholarship_id');
            $table->unsignedTinyInteger('scholarship_discount_percent')->nullable()->after('scholarship_discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table): void {
            $table->dropColumn([
                'allow_partial_payment',
                'requires_partial_approval',
                'partial_payment_approved_at',
                'scholarship_discount_amount',
                'scholarship_discount_percent',
            ]);

            $table->dropConstrainedForeignId('partial_payment_approved_by');
            $table->dropConstrainedForeignId('scholarship_id');
        });
    }
};
