<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Http\Request;
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
                            ->where('productos.descripcion','like','%'.$request['producto'].'%')
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
        // $producto = Producto::Todo()->where('sucursal_id',$id)->groupBy("categorias.grupo")->get();
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
        Producto::create($request->all());
        return $this->listadoCategoria($categoria_id);
    }
    public function show($id){
        return response()->json(Producto::find($id));
    }
    // public function update(Request $request, $id){
    //     $producto=Producto::find($id);
    //     if (!$producto)
    //         return response()->json("Este Productoo no existe",400);
    //     $producto->update($request->all());
    //     return response()->json($producto);
    //     // return $this->listado($categoria_id);
    // }
    public function update( $id,Request $request){
        $producto=Producto::find($id);
        $input=$request->all();
        $producto['descripcion']=$request->get('descripcion');
        $producto['codigo']=$request->get('codigo');
        $producto['stock']=$request->get('stock');
        $producto['cantidad_minima']=$request->get('cantidad_minima');
        $producto['precio_compra']=$request->get('precio_compra');
        $producto['precio_venta']=$request->get('precio_venta');
        if($input['imagen']!="")
            $producto['imagen']=$input['imagen'];
        $producto->save();
        return response()->json($producto);
        //  $this->listadoCategoria($producto->categoria_id);
        // $users = User::select("*")->orderBy("apellido", "asc")->get();
        // return response()->json($users,200);
        // return response()->json(User::get(),200);
    }

    public function destroy($id){
        $p=Producto::find($id);
        $categoria_id=$p['categoria_id'];
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
                // $objeto=new stdClass;
                // $objeto->categoria=$item->grupo;
                // $objeto->productos=$producto;
                array_push($answer,$producto);
            }
            $objeto=new stdClass;
            $objeto->sucursales=$sucursal->numero;
            $objeto->productos=$answer;
            array_push($sucursalArray,$objeto);
        }
        // $producto = Producto::Todo()->where('sucursal_id',$id)->groupBy("categorias.grupo")->get();
        return response()->json($sucursalArray,200);
    }

}


