<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tracking_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_lot_id');
            $table->unsignedBigInteger('user_id'); // For the pilot
            $table->json('route_data');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->timestamps();
            
            $table->foreign('order_lot_id')->references('id')->on('order_lots');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tracking_routes');
    }
};