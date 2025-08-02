o<?php

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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->boolean('client_provides_product')->default(false);
            $table->decimal('client_provided_quantity', 10, 2)->default(0);
            $table->decimal('required_quantity', 10, 2)->default(0);
            $table->decimal('difference_quantity', 10, 2)->default(0); // positive = surplus, negative = shortage
            $table->enum('difference_type', ['surplus', 'shortage', 'exact'])->default('exact');
            $table->boolean('add_surplus_to_inventory')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
