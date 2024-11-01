<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoriaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:30|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'descripcion' => 'nullable|string|max:80',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no debe exceder los 30 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'descripcion.max' => 'La descripción no debe exceder los 80 caracteres.',
        ];
    }
}
