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
        Schema::table('lots', function (Blueprint $table) {
            $table->decimal('hectares', 11, 2)->change();
            $table->decimal('navigation_latitude', 10, 8)->nullable()->after('hectares');
            $table->decimal('navigation_longitude', 11, 8)->nullable()->after('navigation_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lot', function (Blueprint $table) {
            //
        });
    }
};
