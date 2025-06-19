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
        Schema::table('coordinates', function (Blueprint $table) {
            $table->boolean('is_hole')->default(false)->after('sequence_number');
            $table->integer('hole_group')->nullable()->after('is_hole');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coordinates', function (Blueprint $table) {
            //
        });
    }
};
