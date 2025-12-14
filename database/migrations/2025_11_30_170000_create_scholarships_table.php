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
        Schema::create('scholarships', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'nominal'])->default('percentage');
            $table->decimal('amount', 12, 2);
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
