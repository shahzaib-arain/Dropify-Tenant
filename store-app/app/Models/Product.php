<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'name', 'sku', 'description', 
        'price', 'length', 'width', 'height', 'weight'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
      protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $builder->where('tenant_id', tenant()?->id);
        });
    }
}