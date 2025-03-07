<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_receptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->onDelete('cascade');
            $table->date('reception_date');
            $table->decimal('received_quantity_kg', 10, 2);
            $table->integer('received_quantity_units');
            $table->decimal('scrap_quantity_kg', 10, 2)->default(0);
            $table->integer('scrap_quantity_units')->default(0);
            $table->string('pickup_vehicle_plate', 10);
            $table->string('pickup_driver_name', 100);
            $table->string('pickup_driver_id', 20);
            $table->foreignId('received_by')->constrained('users');
            $table->string('delivered_by', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_receptions');
    }
};