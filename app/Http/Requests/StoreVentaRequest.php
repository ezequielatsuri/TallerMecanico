<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
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
            'fecha_hora' => 'required',
            'impuesto' => 'required',
            'numero_comprobante' => 'required|unique:ventas,numero_comprobante|max:255',
            'total' => 'required|numeric',
            'cliente_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'comprobante_id' => 'required|exists:comprobantes,id',

            // Productos
        'arrayidproducto' => 'nullable|array',
        'arrayidproducto.*' => 'exists:productos,id',
        'arraycantidad' => 'nullable|array',
        'arraycantidad.*' => 'integer|min:1',
        'arrayprecioventa' => 'nullable|array',
        'arrayprecioventa.*' => 'numeric|min:0',
        'arraydescuento' => 'nullable|array',
        'arraydescuento.*' => 'numeric|min:0',
        // Servicios
        'arrayidservicio' => 'nullable|array',
        'arrayidservicio.*' => 'exists:servicios,id',
        'arrayprecioservicio' => 'nullable|array',
        'arrayprecioservicio.*' => 'numeric|min:0',
        'arraydescuentoservicio' => 'nullable|array',
        'arraydescuentoservicio.*' => 'numeric|min:0',



        ];
    }
}
