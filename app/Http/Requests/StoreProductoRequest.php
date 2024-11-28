<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'codigo' => 'required|unique:productos,codigo|max:50',
            'nombre' => 'required|unique:productos,nombre|max:80|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048', // Limitar a 2 MB
            'marca_id' => 'required|integer|exists:marcas,id',
            'fabricante_id' => 'required|integer|exists:fabricantes,id',
            'categoria_id' => 'required|integer|exists:categorias,id', // Cambiado de 'categorias' a 'categoria_id'
        ];
    }

    /**
     * Custom attribute names for the validator.
     */
    public function attributes()
    {
        return [
            'marca_id' => 'marca',
            'fabricante_id' => 'fabricante',
            'categoria_id' => 'categoría', // Cambiado de 'categorias' a 'categoria'
        ];
    }

    /**
     * Custom messages for validator errors.
     */
    public function messages()
    {
        return [
            'codigo.required' => 'Se necesita un campo código.',
            'codigo.unique' => 'El código ya está en uso, por favor elige otro.',
            'codigo.max' => 'El código no debe exceder los 50 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'El nombre ya está en uso, por favor elige otro.',
            'nombre.max' => 'El nombre no debe exceder los 80 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'descripcion.max' => 'La descripción no debe exceder los 255 caracteres.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'img_path.image' => 'El archivo debe ser una imagen.',
            'img_path.mimes' => 'La imagen debe ser de tipo: png, jpg, jpeg.',
            'img_path.max' => 'La imagen no debe exceder los 2 MB.',
            'marca_id.required' => 'La marca es obligatoria.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',
            'fabricante_id.required' => 'El fabricante es obligatorio.',
            'fabricante_id.exists' => 'El fabricante seleccionado no es válido.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.integer' => 'La categoría seleccionada no es válida.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }
}
