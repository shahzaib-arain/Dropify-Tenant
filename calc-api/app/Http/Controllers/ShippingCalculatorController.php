<?php
namespace App\Http\Controllers;

use App\Http\Requests\ShippingCalculationRequest;
use App\Models\CalculationFormula;
use App\Models\RateCard;
use App\Models\CalculationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
class ShippingCalculatorController extends Controller
{
    public function calculate(ShippingCalculationRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $validated = $request->validated();
        
        // Get the appropriate rate card
        $rateCard = RateCard::where('transport_mode', $validated['transport_mode'])
            ->where('is_active', true)
            ->whereDate('valid_from', '<=', now())
            ->where(function($query) {
                $query->whereDate('valid_to', '>=', now())
                      ->orWhereNull('valid_to');
            })
            ->firstOrFail();
            
        // Get the calculation formula
        $formula = CalculationFormula::where('formula_type', 'shipping')
            ->where('name', 'like', '%'.$validated['transport_mode'].'%')
            ->where('is_active', true)
            ->firstOrFail();
            
        // Calculate shipping cost
        $cost = $this->calculateShippingCost(
            $validated['weight'],
            $validated['dimensions'],
            $rateCard->rates,
            $formula->formula
        );
        
        // Log the calculation
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);
        
        CalculationLog::create([
            'request_id' => Str::uuid(),
            'endpoint' => 'shipping-cost',
            'input_parameters' => $validated,
            'output_result' => ['cost' => $cost],
            'processing_time_ms' => $processingTime,
            'caller_ip' => $request->ip(),
            'api_client' => $request->header('X-Api-Client')
        ]);
        
        return response()->json([
            'success' => true,
            'cost' => $cost,
            'currency' => 'USD',
            'rate_card_id' => $rateCard->id,
            'formula_used' => $formula->name,
            'processing_time_ms' => $processingTime
        ]);
    }
    
    public function availableMethods(Request $request): JsonResponse
    {
        $methods = RateCard::where('is_active', true)
            ->whereDate('valid_from', '<=', now())
            ->where(function($query) {
                $query->whereDate('valid_to', '>=', now())
                      ->orWhereNull('valid_to');
            })
            ->groupBy('transport_mode')
            ->pluck('transport_mode');
            
        return response()->json([
            'success' => true,
            'methods' => $methods,
            'timestamp' => Carbon::now()->toDateTimeString()
        ]);
    }
    
    protected function calculateShippingCost(float $weight, array $dimensions, array $rates, array $formula): float
    {
        // Implement your specific shipping calculation logic here
        // This is a simplified example - adapt to your actual formulas
        
        $volumetricWeight = ($dimensions['length'] * $dimensions['width'] * $dimensions['height']) / $formula['volumetric_divisor'];
        
        $chargeableWeight = max($weight, $volumetricWeight);
        
        $baseCost = $rates['base_cost'] ?? 0;
        $perKgCost = $rates['rate_per_kg'] ?? 0;
        
        return $baseCost + ($chargeableWeight * $perKgCost);
    }
}