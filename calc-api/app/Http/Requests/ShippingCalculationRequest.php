<?php
// app/Http/Requests/ShippingCalculationRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'weight' => 'required|numeric|min:0.01',
            'dimensions.length' => 'required|numeric|min:0.1',
            'dimensions.width' => 'required|numeric|min:0.1',
            'dimensions.height' => 'required|numeric|min:0.1',
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100',
            'transport_mode' => 'required|in:air,sea,land',
            'options' => 'sometimes|array'
        ];
    }
}