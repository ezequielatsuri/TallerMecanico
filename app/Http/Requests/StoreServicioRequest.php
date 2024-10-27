<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
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
            'codigo' => 'required|unique:servicios,codigo|max:50',
            'nombre' => 'required|unique:servicios,nombre|max:80',
            'descripcion' => 'nullable|max:255',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'codigo' => 'código',
            'nombre' => 'nombre del servicio',
            'descripcion' => 'nullable|max:255',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'codigo.required' => 'El campo código es obligatorio',
            'codigo.unique' => 'El código ya está en uso',
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.unique' => 'El nombre del servicio ya existe',
        ];
    }
}
