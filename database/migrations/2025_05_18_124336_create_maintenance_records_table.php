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
        Schema::create('maintenance_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('car_id')->constrained()->onDelete('cascade');
        $table->foreignId('mechanic_id')->nullable()->constrained()->onDelete('set null');
        $table->date('service_date');
        $table->integer('odometer_reading');
        $table->text('description')->nullable();
        $table->string('first_check')->nullable();
        $table->text('detailed_check')->nullable();
        $table->decimal('cost', 10, 2);
        $table->decimal('discount', 10, 2);
        $table->decimal('due', 10, 2);
        $table->date('next_due_date')->nullable();
        $table->string('status')->default('completed');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
