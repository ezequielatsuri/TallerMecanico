<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresentacioneRequest extends FormRequest
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
        $fabricante = $this->route('fabricante');
        $caracteristicaId = $fabricante->caracteristica->id;

        return [
            'nombre' => 'required|max:60|unique:caracteristicas,nombre,' . $caracteristicaId . '|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'descripcion' => 'nullable|max:255',
        ];
    }
    
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no debe exceder los 60 caracteres.',
            'nombre.unique' => 'El nombre ya está en uso. Por favor elija otro.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',
        ];
    }
}
