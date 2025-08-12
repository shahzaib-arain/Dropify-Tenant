<?php
// app/Http/Requests/PricingCalculationRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PricingCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'base_price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:1',
            'pricing_strategy' => 'required|string|max:50',
            'options' => 'sometimes|array',
            'options.*' => 'string|max:50'
        ];
    }
}