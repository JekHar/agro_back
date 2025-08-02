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
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Add new fields for simplified structure
            $table->boolean('client_provided')->default(false)->after('product_id');
            $table->decimal('quantity', 10, 2)->default(0)->after('client_provided');
            $table->foreignId('merchant_id')->nullable()->constrained()->onDelete('set null')->after('add_surplus_to_inventory');

            // Remove old complex fields that are no longer needed
            $table->dropColumn([
                'client_provides_product',
                'client_provided_quantity',
                'difference_quantity',
                'difference_type'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Restore old fields
            $table->boolean('client_provides_product')->default(false)->after('product_id');
            $table->decimal('client_provided_quantity', 10, 2)->default(0)->after('client_provides_product');
            $table->decimal('difference_quantity', 10, 2)->default(0)->after('required_quantity');
            $table->enum('difference_type', ['surplus', 'shortage', 'exact'])->default('exact')->after('difference_quantity');

            // Remove new fields
            $table->dropColumn([
                'client_provided',
                'quantity',
                'add_surplus_to_inventory',
                'merchant_id'
            ]);
        });
    }
};
