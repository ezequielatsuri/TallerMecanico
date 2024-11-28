<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'fecha_vencimiento',
        'marca_id',
        'fabricante_id',
        'img_path'
    ];

    public function compras()
    {
        return $this->belongsToMany(Compra::class)
                    ->using(CompraProducto::class)
                    ->withTimestamps()
                    ->withPivot('cantidad', 'precio_compra', 'precio_venta');
    }

    public function ventas()
    {
        return $this->belongsToMany(Venta::class)->withTimestamps()
                    ->withPivot('cantidad', 'precio_venta', 'descuento');
    }

    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class);
    }

    public function ultimaCompraProducto()
    {
        return $this->hasOne(CompraProducto::class)->latestOfMany();
    }

    public function handleUploadImage($image)
    {
        $file = $image;
        $name = time() . $file->getClientOriginalName();
        // $file->move(public_path() . '/img/productos/', $name);
        Storage::putFileAs('/public/productos/', $file, $name, 'public');

        return $name;
    }
}
