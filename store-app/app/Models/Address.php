<?php

// app/Models/Address.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'tenant_id', 'type',
        'line1', 'city', 'state', 'country', 'postal_code'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}