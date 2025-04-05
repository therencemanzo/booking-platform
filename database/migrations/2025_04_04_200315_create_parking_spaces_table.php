<?php

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
        Schema::create('parking_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "A1", "B4"
            $table->decimal('length', 5, 2)->nullable(); // in meters
            $table->decimal('width', 5, 2)->nullable();
            $table->boolean('is_covered')->default(false);
            $table->boolean('has_ev_charger')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spaces');
    }
};
