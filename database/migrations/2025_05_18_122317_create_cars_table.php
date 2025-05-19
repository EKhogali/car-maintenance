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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('vin')->unique();
            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->integer('mileage')->default(0);
            $table->string('engine_type')->nullable();
            $table->string('transmission')->nullable(); // e.g., automatic/manual
            $table->text('notes')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
