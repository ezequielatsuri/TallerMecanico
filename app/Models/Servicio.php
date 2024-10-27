<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];


    public function ventas()
    {
        return $this->belongsToMany(Venta::class)->withTimestamps()
            ->withPivot('precio', 'descuento');
    }
}
