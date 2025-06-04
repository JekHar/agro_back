<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->decimal('concentration', 11, 2)->nullable()->change();
            $table->decimal('application_volume_per_hectare', 11, 2)->nullable()->change();
            $table->integer('stock')->nullable()->change();
            $table->decimal('dosage_per_hectare', 11, 2)->nullable()->change();
            $table->string('commercial_brand')->nullable()->after('name');
            $table->decimal('liters_per_can', 8, 2)->nullable()->after('dosage_per_hectare');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
