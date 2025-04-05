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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        Schema::create('parking_space_price', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_space_id')->constrained('parking_spaces')->onDelete('cascade');
            $table->foreignId('price_id')->constrained('prices')->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
        Schema::dropIfExists('parking_space_price');
    }
};
