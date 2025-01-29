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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');

            $table->decimal('client_provided_quantity', 11, 2);
            $table->decimal('manual_total_quantity', 11, 2);
            $table->decimal('manual_dosage_per_hectare', 11, 2);

            $table->decimal('total_quantity_to_use', 11, 2);
            $table->decimal('calculated_dosage', 11, 2);
            $table->decimal('product_difference', 11, 2);
            $table->string('difference_observation')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
