<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\historial;
use App\Models\Producto;
use App\Models\ProductosLog;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use stdClass;

class ProductoController extends Controller
{
    public function index()
    {
        $producto = Producto::Todo()->orderBy("categorias.grupo", "asc")->get();
        return response()->json($producto,200);
    }
    public function listadoVenta($id){
        $productos=Producto::Todo()->where('categorias.sucursal_id',$id)->get();
        return response()->json($productos);
    }
    public function buscar(Request $request){
        $producto = Producto::Todo()
                            ->where('productos.id',$request['producto'])
                            ->orWhere('productos.descripcion','like','%'.$request['producto'].'%')
                            ->orWhere('categorias.grupo','like','%'.$request['producto'].'%')
                            ->orWhere('productos.codigo','like','%'.$request['producto'].'%')
                            ->get();
        return response()->json($producto);
    }
    public function listadoSucursal($id)
    {
        $categorias=Categoria::where('sucursal_id',$id)->get();
        $answer=[];
        foreach($categorias as $item){
            $producto = Producto::Todo()->where('categoria_id',$item->id)->orderBy("categorias.grupo",'asc')->get();
            $objeto=new stdClass;
            $objeto->categoria=$item->grupo;
            $objeto->productos=$producto;
            array_push($answer,$objeto);
        }
        return response()->json($answer,200);
    }
    public function listadoCategoria($id)
    {
        $producto=Producto::where('categoria_id',$id)->orderBy("categoria_id","asc")->get();
        return response()->json($producto,200);
    }
    public function store(Request $request){
        $categoria_id=$request['categoria_id'];
        $request->validate([
            'descripcion' => 'required',
            'imagen' => 'required',
        ]);
        $item=Producto::create($request->all());
        $user = Auth::guard('api')->user();
        $fecha=Carbon::now()->format('Y-m-d H:i:s');
        $this->addLogs($item,$user,'Primer Ingreso del producto',$item->stock,0,$fecha);
        return $this->listadoCategoria($categoria_id);
    }
    public function show($id){
        return response()->json(Producto::Todo()->where('productos.id',$id)->first());
    }
    public function verlog($id){
        $producto=Producto::Todo()->where('productos.id',$id)->get()->first();
        $items=ProductosLog::where('producto_id',$id)->get();
        foreach($items as $item){
            $item->producto_id= $producto->categoriaGrupo.' '.$producto->descripcion;
            $user=User::find($item->user_id);
            $item->user_id=$user->username;
        }
        return response()->json($items);
    }
    public function update( $id,Request $request){
        $producto=Producto::find($id);
        $input=$request->all();
        $producto['descripcion']=$request->get('descripcion');
        $producto['codigo']=$request->get('codigo');
        $producto['stock']=$producto['stock']+$request->get('stock');
        $producto['cantidad_minima']=$request->get('cantidad_minima');
        $producto['precio_compra']=$request->get('precio_compra');
        $producto['precio_venta']=$request->get('precio_venta');
        if($input['imagen']!="")
            $producto['imagen']=$input['imagen'];
        $producto->save();
        return response()->json($producto);
    }
    public function actualizarstok($id,Request $request){
            $user = Auth::guard('api')->user();
            $ingreso=0;
            $egreso=0;
            $descripcion='Ingreso de '.$request->get('stock').' unidades por actualizacion de stock';
            $ingreso=$request->get('stock');
            $producto=Producto::find($id);
            $producto['stock']=$producto['stock']+$request->get('stock');
            $producto['precio_compra']=$request->get('precio_compra');
            $producto['precio_venta']=$request->get('precio_venta');
            $producto->save();
            $fecha=Carbon::now()->format('Y-m-d H:i:s');
            $this->addLogs($producto,$user,$descripcion,$ingreso,$egreso,$fecha);
            return response()->json($producto);
    }
    public function destroy($id){
        $p=Producto::find($id);
        $categoria_id=$p['categoria_id'];
        $logs=ProductosLog::where('producto_id',$id)->get();
        if($logs->count() > 0){
            foreach ($logs as $item){
                $item->delete();
            }
        }
        $p->delete();
        return $this->listadoCategoria($categoria_id);
    }
    public function imageUpload(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);
        $imagen=$request->file('image');
        $path_img='producto';
        $imageName = $path_img.'/'.$imagen->getClientOriginalName();
        try {
            Storage::disk('public')->put($imageName, File::get($imagen));
        }
        catch (\Exception $exception) {
            return response('error',400);
        }
        return response()->json(['image' => $imageName]);
    }

    public function image($nombre){
        return response()->download(public_path('storage').'/producto/'.$nombre,$nombre);
    }
    public function listadoSucursales($id)
    {
        $sucursales=Sucursal::get();
        $sucursalArray=[];
        $i=0;
        foreach($sucursales as $sucursal){
            $answer=[];
            $categorias=Categoria::where('sucursal_id',$sucursal->id)->get();
            foreach($categorias as $item){
                $producto = Producto::Todo()->where('categoria_id',$item->id)->orderBy("categorias.grupo",'asc')->get();
                array_push($answer,$producto);
            }
            $objeto=new stdClass;
            $objeto->sucursales=$sucursal->numero;
            $objeto->productos=$answer;
            array_push($sucursalArray,$objeto);
        }
        return response()->json($sucursalArray,200);
    }
    public function addLogs($item,$user,$descripcion,$ingreso,$egreso,$fecha){
        ProductosLog::create([
            'producto_id'=>$item->id,
            'user_id'=>$user->id,
            'descripcion'=>$descripcion,
            'ingreso'=>$ingreso,
            'egreso'=>$egreso,
            'fecha'=>$fecha,
        ]);
    }
    public function actualizarLogs($id){
        $productos=Producto::All();
        // dd($productos);
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
    }
}
