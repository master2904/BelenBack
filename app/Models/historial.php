<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class historial extends Model
{
    use HasFactory;
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio'
    ];
    public function scopeProducto($query){
        return $query
            ->join('productos','productos.id','historials.producto_id')
            ->join('categorias','categorias.id','productos.categoria_id')
            ->select(
                'historials.cantidad',
                'historials.precio',
                DB::raw('concat(categorias.grupo," ",productos.descripcion) as nombre'),
            );
    }
}
