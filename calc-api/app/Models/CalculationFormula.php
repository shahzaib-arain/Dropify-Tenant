<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalculationFormula extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'formula_type', 'formula', 
        'description', 'is_active'
    ];

    protected $casts = [
        'formula' => 'array', // If storing JSON
        'is_active' => 'boolean'
    ];
}