<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'codigo',
        'descripcion',
        'imagen',
        'stock',
        'cantidad_minima',
        'precio_compra',
        'precio_venta',
        'categoria_id'
    ];
    public function scopeTodo($query){
        $query->join('categorias','categorias.id','productos.categoria_id')
        ->join('sucursals','sucursals.id','categorias.sucursal_id')
        ->select('productos.codigo',
                DB::raw('concat(categorias.grupo," ",productos.descripcion) as descripcion'),
                'productos.id',
                'productos.imagen',
                'productos.stock',
                'productos.precio_venta',
                'productos.precio_compra',
                'productos.cantidad_minima',
                'sucursals.direccion');
    }
}
