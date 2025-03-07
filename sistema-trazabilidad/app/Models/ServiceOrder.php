<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    protected $fillable = [
        'order_number',
        'service_type_id',
        'provider_id',
        'product_id',
        'quantity_kg',
        'quantity_units',
        'service_cost',
        'request_date',
        'commitment_date',
        'delivery_vehicle_plate',
        'delivery_driver_name',
        'delivery_driver_id',
        'delivery_driver_phone',
        'delivered_by',
        'received_by',
        'status'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrder = self::orderBy('id', 'desc')->first();
            $nextNumber = $lastOrder ? intval(substr($lastOrder->order_number, 3)) + 1 : 1001;
            $order->order_number = 'OS-' . $nextNumber;
        });
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reception()
    {
        return $this->hasOne(ServiceReception::class);
    }
}
