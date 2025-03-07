<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name', 'contact_name', 'phone', 'address', 'email'];

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}