<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
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
        $producto = $this->route('producto');
        return [
            'codigo' => 'required|unique:productos,codigo,' . $producto->id . '|max:50',
            'nombre' => 'required|unique:productos,nombre,' . $producto->id . '|max:80|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/',
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date|after:today',
            'img_path' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'marca_id' => 'required|integer|exists:marcas,id',
            'fabricante_id' => 'required|integer|exists:fabricantes,id',
            'categorias' => 'required|array'
        ];
    }

    public function attributes()
    {
        return [
            'marca_id' => 'marca',
            'fabricante_id' => 'fabricante',
            'categorias' => 'categorías',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'Se necesita un campo código.',
            'codigo.unique' => 'El código ya está en uso, por favor elija otro.',
            'nombre.required' => 'Se necesita un campo nombre.',
            'nombre.unique' => 'El nombre ya está en uso, por favor elija otro.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',
            'descripcion.max' => 'La descripción no puede exceder los 255 caracteres.',
            'fecha_vencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser una fecha futura.',
            'img_path.image' => 'El archivo debe ser una imagen.',
            'img_path.mimes' => 'La imagen debe ser de tipo: png, jpg, jpeg.',
            'img_path.max' => 'La imagen no puede pesar más de 2 MB.',
            'marca_id.required' => 'La marca es obligatoria.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',
            'fabricante_id.required' => 'El fabricante es obligatorio.',
            'fabricante_id.exists' => 'El fabricante seleccionado no es válido.',
            'categorias.required' => 'Debe seleccionar al menos una categoría.',
            'categorias.array' => 'Las categorías seleccionadas no son válidas.'
        ];
    }
}
