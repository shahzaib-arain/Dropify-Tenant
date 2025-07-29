<?php
// app/Http/Controllers/BatchCalculatorController.php
namespace App\Http\Controllers;

use App\Http\Requests\BatchCalculationRequest;
use App\Models\CalculationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BatchCalculatorController extends Controller
{
    public function handle(BatchCalculationRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $validated = $request->validated();
        $results = [];
        
        foreach ($validated['calculations'] as $calculation) {
            try {
                // Route to appropriate calculator based on type
                switch ($calculation['type']) {
                    case 'shipping':
                        $results[] = $this->processShippingCalculation($calculation);
                        break;
                    case 'pricing':
                        $results[] = $this->processPricingCalculation($calculation);
                        break;
                    default:
                        $results[] = [
                            'success' => false,
                            'error' => 'Invalid calculation type',
                            'type' => $calculation['type']
                        ];
                }
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'type' => $calculation['type'] ?? 'unknown'
                ];
            }
        }
        
        // Log the batch calculation
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);
        
        CalculationLog::create([
            'request_id' => Str::uuid(),
            'endpoint' => 'batch-calculate',
            'input_parameters' => ['count' => count($validated['calculations'])],
            'output_result' => ['total_calculations' => count($results)],
            'processing_time_ms' => $processingTime,
            'caller_ip' => $request->ip(),
            'api_client' => $request->header('X-Api-Client')
        ]);
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'total_calculations' => count($results),
            'processing_time_ms' => $processingTime,
            'timestamp' => Carbon::now()->toDateTimeString()
        ]);
    }
    
    protected function processShippingCalculation(array $data): array
    {
        // This would use similar logic to ShippingCalculatorController
        // Simplified for example purposes
        
        return [
            'success' => true,
            'type' => 'shipping',
            'cost' => 25.99, // Calculated value
            'currency' => 'USD'
        ];
    }
    
    protected function processPricingCalculation(array $data): array
    {
        // This would use similar logic to PricingCalculatorController
        // Simplified for example purposes
        
        return [
            'success' => true,
            'type' => 'pricing',
            'price' => 49.99, // Calculated value
            'currency' => 'USD'
        ];
    }
}