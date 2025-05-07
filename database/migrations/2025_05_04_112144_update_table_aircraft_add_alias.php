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
        Schema::table('aircrafts', function (Blueprint $table) {
            $table->string('alias')->nullable()->after('brand');
            $table->date('acquisition_date')->nullable()->change();
            $table->integer('manufacturing_year')->nullable()->change();
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
