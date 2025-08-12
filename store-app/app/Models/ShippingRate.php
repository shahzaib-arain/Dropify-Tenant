<?php

// app/Models/ShippingRate.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_method_id', 'tenant_id', 'base_cost', 
        'rate_per_kg', 'rate_per_cm3', 'rate_per_km', 'flat_rate'
    ];

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}