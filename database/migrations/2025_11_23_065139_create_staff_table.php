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
        Schema::create('staff', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete()
                ->unique();
            $table->string('staff_number')->unique();
            $table->string('staff_name')->index();
            $table->string('gender', 10)->nullable()->index();
            $table->string('role', 50)->index();
            $table->date('joined_on')->index();
            $table->string('phone', 30)->nullable()->index();
            $table->text('address')->nullable();
            $table->char('province_id', 2)->nullable()->index();
            $table->char('regency_id', 4)->nullable()->index();
            $table->char('district_id', 7)->nullable()->index();
            $table->char('village_id', 10)->nullable()->index();
            $table->string('education_level', 100)->nullable()->index();
            $table->string('education_institution')->nullable();
            $table->year('graduated_year')->nullable()->index();
            $table->string('emergency_contact_name')->nullable()->index();
            $table->string('emergency_contact_phone', 30)->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->softDeletes()->index();

            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('regency_id')
                ->references('id')
                ->on('regencies')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
