<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'cliente_id',
        'fecha',
        'total'
    ];
    public function scopeShow($query){
        return $query
            ->join('users','users.id','ventas.user_id')
            ->join('clientes','clientes.id','ventas.cliente_id')
            ->select(
                'ventas.id',
                'ventas.fecha',
                'ventas.total',
                'clientes.nombre',
                'clientes.nit',
                'users.username'
            );
    }
}
