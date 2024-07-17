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
                'productos.descripcion',
                'productos.id',
                'productos.imagen',
                'productos.stock',
                'productos.precio_venta',
                'productos.precio_compra',
                'productos.cantidad_minima',
                'productos.categoria_id',
                'sucursals.direccion',
                'sucursals.numero',
                'categorias.grupo as categoriaGrupo',
                'categorias.codigo as cod');
    }
    public function scopeMeses($query,$sucursalId,$gestion,$mes){
        $query
            ->join('categorias','categorias.id','productos.categoria_id')
            ->join('sucursals','sucursals.id','categorias.sucursal_id')
            ->join('historials','historials.producto_id','productos.id')
            ->join('ventas','ventas.id','historials.venta_id')
            ->select(
                DB::raw('concat(categorias.grupo," ",productos.descripcion) as name'),
                DB::raw('sum(historials.cantidad) as value')
            )
            ->where('categorias.sucursal_id',$sucursalId)
            ->whereYear('ventas.fecha',$gestion)
            ->whereMonth('ventas.fecha',$mes)
            ->groupBy('productos.id')
            ->groupBy('categorias.grupo')
            ->groupBy('productos.descripcion');
        return $query;
    }
}
