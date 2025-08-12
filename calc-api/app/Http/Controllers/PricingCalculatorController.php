<?php
namespace App\Http\Controllers;

use App\Http\Requests\PricingCalculationRequest;
use App\Models\CalculationFormula;
use App\Models\CalculationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PricingCalculatorController extends Controller
{
    public function calculate(PricingCalculationRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $validated = $request->validated();
        
        // Get the pricing formula
        $formula = CalculationFormula::where('formula_type', 'pricing')
            ->where('name', $validated['pricing_strategy'])
            ->where('is_active', true)
            ->firstOrFail();
            
        // Calculate product price
        $price = $this->calculateProductPrice(
            $validated['base_price'],
            $validated['quantity'],
            $formula->formula,
            $validated['options'] ?? []
        );
        
        // Log the calculation
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);
        
        CalculationLog::create([
            'request_id' => Str::uuid(),
            'endpoint' => 'product-price',
            'input_parameters' => $validated,
            'output_result' => ['price' => $price],
            'processing_time_ms' => $processingTime,
            'caller_ip' => $request->ip(),
            'api_client' => $request->header('X-Api-Client')
        ]);
        
        return response()->json([
            'success' => true,
            'price' => $price,
            'currency' => 'USD',
            'formula_used' => $formula->name,
            'processing_time_ms' => $processingTime
        ]);
    }
    
    protected function calculateProductPrice(float $basePrice, int $quantity, array $formula, array $options): float
    {
        // Implement your specific pricing calculation logic here
        // This is a simplified example
        
        $price = $basePrice;
        
        // Apply quantity discount if applicable
        if ($quantity >= ($formula['bulk_quantity'] ?? 0)) {
            $price *= (1 - ($formula['bulk_discount'] ?? 0));
        }
        
        // Apply any additional pricing rules from options
        foreach ($options as $option) {
            if (isset($formula['option_modifiers'][$option])) {
                $price += $formula['option_modifiers'][$option];
            }
        }
        
        return round($price, 2);
    }
}