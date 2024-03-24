<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
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
    public function listadoSucursal($id)
    {
        $categorias=Categoria::where('sucursal_id',$id)->get();
        $i=0;
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
    public function update(Request $request, $id){
        $producto=Producto::find($id);
        $categoria_id=$producto->categoria_id;
        if (!$producto)
            return response()->json("Este Productoo no existe",400);
        $producto->update($request->all());
        return response()->json($producto);
        // return $this->listado($categoria_id);
    }
    // public function update(Request $request, $id){
    //     $producto=Producto::find($id);
    //     $categoria_id=$producto->categoria_id;
    //     if (!$producto)
    //         return response()->json("Este Productoo no existe",400);
    //     $producto->update($request->all());
    //     return $this->listado($categoria_id);
    // }

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

}


