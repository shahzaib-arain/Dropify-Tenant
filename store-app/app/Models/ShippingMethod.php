<?php

// app/Models/ShippingMethod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'name', 'mode'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rate()
{
    return $this->hasOne(ShippingRate::class);
}

    public function shippingLogs()
    {
        return $this->hasMany(ShippingLog::class);
    }
}