<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class GetParkingSpaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_from' => 'required_with:date_to|date|before_or_equal:date_to',
            'date_to' => 'required_with:date_from|date|after_or_equal:date_from',
        ];
    }
}
