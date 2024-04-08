<?php
namespace App\Http\Controllers;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Historial;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\Auth;
class VentaController extends Controller
{
    public function index(){
        $lista=Venta::get();
        return response()->json($lista,200);
    }
    public function meses($sucursalId,$gestion){
        $reportes=[];
        for($i=1;$i<=12;$i++){
            array_push($reportes,Producto::Meses($sucursalId,$gestion,$i)->limit(10)->get());
            // $reportes[$i]=Producto::Meses($gestion,$i)->get();
        }
        return response()->json($reportes);
    }
    public function listarFecha($fechaInicio,$fechaFin){
        $i=0;
        $ventas=Venta::Show()->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])->get();
        foreach($ventas as $venta){
            $ventas[$i]->historial=Historial::Producto()->where('venta_id',$venta->id)->get();
            $i++;
        }
        return response()->json($ventas);
    }
    public function buscar($ventaId){
        $venta=new stdClass;
        $venta->venta=Venta::Show()->where('ventas.id',$ventaId)->get()->first();
        $venta->historial=Historial::Producto()->where('venta_id',$ventaId)->get();
        return response()->json($venta);
    }
    public function store(Request $request){
        $user = Auth::guard('api')->user();
        $cliente=$request->cliente;
        $historial=$request->historial;
        $venta=$request->venta;
        if($cliente['id']==0){
            $clienteNuevo=new Cliente();
            $clienteNuevo->nit=$cliente['nit'];
            $clienteNuevo->nombre=$cliente['nombre'];
            $clienteNuevo->save();
            $venta['cliente_id']=$clienteNuevo->id;
            $cliente=$clienteNuevo;
        }
        $ventaNueva = new Venta();
        $ventaNueva->id=0;
        $ventaNueva->user_id=$user->id;
        $ventaNueva->cliente_id=$venta['cliente_id'];
        $ventaNueva->fecha=Carbon::now()->format('Y-m-d H:i:s');
        $ventaNueva->total=$venta['total'];
        $ventaNueva->save();

        foreach($historial as $h){
            $item=new Historial();
            $item->venta_id=$ventaNueva->id;
            $item->producto_id=$h['producto_id'];
            $item->cantidad=$h['cantidad'];
            $item->precio=$h['precio'];
            $item->save();
            $producto=Producto::find($item->producto_id);
            $producto->stock-=$item->cantidad;
            $producto->save();
        }
        return $this->buscar($ventaNueva->id);
    }
    public function show($id){
        return response()->json(Venta::find($id));
    }
    public function update(Request $request, $id){
        $problema=Venta::find($id);
        if (!$problema)
            return response()->json("Esta venta no existe",400);
        $problema->update($request->all());
        return $this->listar($request->input('id_product'));
    }
    public function eliminar ($id,$id_p){
        Venta::find($id)->delete();
        return $this->listar($id_p);
    }
    public function delete($id){
        $lista = Venta::find($id);
        $valor=$lista->id_prodcut;
        $lista->delete();
        return $this->listar($valor);
    }
}
