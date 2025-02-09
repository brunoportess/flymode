<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightOrderUpdateRequest extends FormRequest
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
            'destino' => 'string|max:255',
            'data_ida' => 'date|after_or_equal:today',
            'data_volta' => 'date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'data_ida.after_or_equal' => 'A data de ida deve ser hoje ou uma data futura.',
            'data_volta.after' => 'A data de volta deve ser posterior à data de ida.',
        ];
    }


}
