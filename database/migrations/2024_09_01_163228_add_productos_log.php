<?php

use App\Models\Cliente;
use App\Models\historial;
use App\Models\Producto;
use App\Models\ProductosLog;
use App\Models\Venta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $productos=Producto::All();
        foreach($productos as $item){
            $historial = historial::where('producto_id',$item->id)->get()->first();
            $sumaCantidad = historial::where('producto_id', $item->id)->sum('cantidad');
            if($historial!=null){
                $venta=Venta::where('id',$historial->venta_id)->get()->first();
                ProductosLog::create([
                    'producto_id'=>$item->id,
                    'user_id'=>$venta->user_id,
                    'descripcion'=>'Primer Ingreso del producto',
                    'ingreso'=>$item->stock+$sumaCantidad,
                    'egreso'=>0,
                    'fecha'=>Carbon::parse($item->created_at)->format('Y-m-d H:i:s')
                ]);
            }
        }
        $ventas=Venta::all();
        foreach($ventas as $venta){
            $cliente=Cliente::find($venta->cliente_id);
            $historiales = historial::where('venta_id',$venta->id)->get();
            foreach($historiales as $historial){
                ProductosLog::create([
                    'producto_id'=>$historial->producto_id,
                    'user_id'=>$venta->user_id,
                    'descripcion'=>'Venta de producto a '.$cliente->nombre,
                    'ingreso'=>0,
                    'egreso'=>$historial->cantidad,
                    'fecha'=>Carbon::parse($historial->created_at)->format('Y-m-d H:i:s')
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
