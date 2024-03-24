<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'grupo',
        'sucursal_id'
    ];
    public function scopeSucursal($query,$sucursalId){
        return $query->join('sucursals','sucursals.id','sucursal_id')
        ->select('categorias.id','categorias.grupo','categorias.sucursal_id');
    }
}
