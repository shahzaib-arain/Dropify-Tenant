<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalculationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'endpoint', 'input_parameters',
        'output_result', 'processing_time_ms', 'caller_ip', 'api_client'
    ];

    protected $casts = [
        'input_parameters' => 'array',
        'output_result' => 'array'
    ];
}
