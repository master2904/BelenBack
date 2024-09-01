<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'producto_id',
        'user_id',
        'descripcion',
        'ingreso',
        'egreso',
        'fecha',
    ];

}
