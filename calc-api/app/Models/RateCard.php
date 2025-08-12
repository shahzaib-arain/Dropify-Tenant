<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RateCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'carrier', 'transport_mode',
        'rates', 'valid_from', 'valid_to', 'is_active'
    ];

    protected $casts = [
        'rates' => 'array',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereDate('valid_from', '<=', now())
            ->where(function($q) {
                $q->whereDate('valid_to', '>=', now())
                  ->orWhereNull('valid_to');
            });
    }
}