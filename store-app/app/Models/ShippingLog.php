<?php

// app/Models/ShippingLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingLog extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'shipping_method_id', 'rate_calculated'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
}