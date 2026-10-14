<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentacionProducto extends Model
{
    use HasFactory;

    protected $table = 'presentaciones_producto';

    protected $fillable = [
        'producto_id',
        'nombre',
        'cantidad_unidades',
        'precio_compra',
        'precio_venta',
        'estado',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}