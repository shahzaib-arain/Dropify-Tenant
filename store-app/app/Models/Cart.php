<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'customer_id', 'status'];
    
    const STATUS_OPEN = 'open';
    const STATUS_CONVERTED = 'converted';
    const STATUS_ABANDONED = 'abandoned';

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
        public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }
}