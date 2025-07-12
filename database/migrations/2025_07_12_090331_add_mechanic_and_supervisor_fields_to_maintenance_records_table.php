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
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->decimal('mechanic_amount', 10, 2)->nullable()->after('mechanic_pct');
            $table->decimal('supervisor_pct', 5, 2)->default(10)->after('mechanic_amount');
            $table->decimal('supervisor_amount', 10, 2)->nullable()->after('supervisor_pct');
            $table->decimal('company_amount', 10, 2)->nullable()->after('supervisor_pct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn(['mechanic_amount', 'supervisor_pct', 'supervisor_amount', 'company_amount']);
        });
    }
};
