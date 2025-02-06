<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightOrderStoreRequest extends FormRequest
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
            'solicitante' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'data_ida' => 'required|date|after_or_equal:today',
            'data_volta' => 'nullable|date|after:data_ida',
            'status' => ['required', Rule::in(['S', 'A', 'C'])],
        ];
    }

    public function messages(): array
    {
        return [
            'solicitante.required' => 'O nome do solicitante é obrigatório.',
            'destino.required' => 'O destino é obrigatório.',
            'data_ida.required' => 'A data de ida é obrigatória.',
            'data_ida.after_or_equal' => 'A data de ida deve ser hoje ou uma data futura.',
            'data_volta.after' => 'A data de volta deve ser posterior à data de ida.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: S - solicitado, A - aprovado ou C - cancelado.',
        ];
    }
}
