<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calculations' => 'required|array|max:50',
            'calculations.*.type' => 'required|in:shipping,pricing',
            'calculations.*.data' => 'required|array'
        ];
    }
}