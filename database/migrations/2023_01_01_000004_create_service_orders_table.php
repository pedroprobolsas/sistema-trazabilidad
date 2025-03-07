<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('service_type_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->decimal('quantity_kg', 10, 2);
            $table->integer('quantity_units');
            $table->decimal('service_cost', 12, 2);
            $table->date('request_date');
            $table->date('commitment_date');
            $table->string('delivery_vehicle_plate', 10);
            $table->string('delivery_driver_name', 100);
            $table->string('delivery_driver_id', 20);
            $table->string('delivery_driver_phone', 20);
            $table->foreignId('delivered_by')->constrained('users');
            $table->string('received_by', 100);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_orders');
    }
};