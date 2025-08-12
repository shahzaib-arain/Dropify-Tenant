<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShippingCalculatorController;
use App\Http\Controllers\PricingCalculatorController;
use App\Http\Controllers\BatchCalculatorController;

Route::middleware('auth:api')->group(function () {
    // Shipping Calculations
    Route::post('/shipping-cost', [ShippingCalculatorController::class, 'calculate']);
    Route::post('/shipping-methods', [ShippingCalculatorController::class, 'availableMethods']);
    
    // Pricing Calculations
    Route::post('/product-price', [PricingCalculatorController::class, 'calculate']);
    
    // Batch Calculations
    Route::post('/batch-calculate', [BatchCalculatorController::class, 'handle']);
});