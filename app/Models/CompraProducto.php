<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CompraProducto extends Pivot
{
    protected $table = 'compra_producto';

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_compra',
        'precio_venta',
        // Agrega otros campos si existen
    ];
    // Si deseas manejar las marcas de tiempo
    public $timestamps = true;
}
