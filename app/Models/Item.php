<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'proveedor_id',
        'producto_id',
        'nuevo'
    ];
    public function scopeItemProveedor($query,$id){
        return $query
            ->join('proveedors','proveedors.id','items.proveedor_id')
            ->where('proveedors.id',$id);
    }
}
