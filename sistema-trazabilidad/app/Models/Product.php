<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}