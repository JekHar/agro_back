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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('aircraft_id');
            $table->unsignedBigInteger('pilot_id');
            $table->unsignedBigInteger('ground_support_id');
            $table->decimal('total_hectares', 11, 3);
            $table->decimal('total_amount', 11, 2);
            $table->enum('status', ['draft', 'pending', 'in_progress', 'completed', 'cancelled']);
            $table->timestamp('scheduled_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('merchants');
            $table->foreign('tenant_id')->references('id')->on('merchants');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('aircraft_id')->references('id')->on('aircrafts');
            $table->foreign('pilot_id')->references('id')->on('users');
            $table->foreign('ground_support_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
