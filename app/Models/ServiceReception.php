<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReception extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_order_id',
        'reception_date',
        'received_quantity_kg',
        'received_quantity_units',
        'scrap_quantity_kg',
        'scrap_quantity_units',
        'pickup_vehicle_plate',
        'pickup_driver_name',
        'pickup_driver_id',
        'received_by',
        'delivered_by',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}