<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Concurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ConcursoController extends Controller
{
    public function activo($id){
        $concurso=Concurso::where('estado',$id)->get();
        $categorias=Categoria::where('id_concurso',$concurso[0]->id)->get();
        return response()->json(array($concurso,$categorias));
    }
    public function index()    {
        return response()->json(Concurso::get(),200);    
    }
    public function edit($id){
        $concurso=Concurso::find($id);
        $consulta=DB::update('update concursos set estado =0 where id!=:id',['id'=> $id]);
        $consulta=DB::update('update concursos set estado =1 where id=:id',['id'=> $id]);
        return response()->json(Concurso::get(),200);
    }
    public function store(Request $request)
    {        
        $request->validate([
            'titulo' => 'required',
        ]);
        Concurso::create($request->all());
        $concurso=Concurso::get();
        return response()->json( Concurso::get());
    }

    public function show($id)
    {
        return response()->json(Concurso::find($id));
    }

    public function update(Request $request, $id)
    {
        // return response()->json(($request));
        $concurso=Concurso::find($id);
        $input=$request->all();
        if($input['imagen']=="")
            $input['imagen']=$concurso->imagen; 
        $concurso->update($input);
            return response()->json(Concurso::get());
    }

    public function destroy ($id)
    {
        Concurso::find($id)->delete();
        return response()->json(Concurso::get());
    }
    public function subir(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imagen=$request->file('image');
        $path_img='concurso';
        $imageName = $path_img . '/' . $imagen->getClientOriginalName();
        try {
            Storage::disk('public')->put($imageName, File::get($imagen));
        }
        catch (\Exception $exception) {
            return response('error',400);
        }
        return response()->json(['image' => $imageName]);
    }
    public function image($nombre){
        return response()->download(public_path('storage').'/concurso/'.$nombre,$nombre);
    }
}
