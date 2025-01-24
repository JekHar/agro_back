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
        Schema::create('flight_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_id');
            $table->unsignedBigInteger('lot_id');
            $table->decimal('lot_total_hectares', 11, 3);
            $table->decimal('hectares_to_apply', 11, 3);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->foreign('lot_id')->references('id')->on('lots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_lots');
    }
};
