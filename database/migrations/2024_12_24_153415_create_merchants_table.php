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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('trade_name')->nullable();
            $table->bigInteger('fiscal_number');
            $table->string('main_activity')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->enum('merchant_type', ['client', 'tenant']);
            $table->timestamp('disabled_at')->nullable();
            $table->unsignedBigInteger('merchant_id')->nullable();
            $table->string('locality');
            $table->string('address');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
