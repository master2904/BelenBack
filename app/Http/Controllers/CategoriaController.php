<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index(){
        return response()->json(Categoria::get(),200);
    }
    public function listarSucursal($sucursalId){
        $categorias=Categoria::where('sucursal_id',$sucursalId)->get();
        return response()->json($categorias);
    }
    public function store(Request $request){
        $request->validate([
            'grupo' => 'required'
        ]);
        Categoria::create($request->all());
        return $this->listarSucursal($request->input('sucursal_id'));
    }

    public function show($id){
        return response()->json(Categoria::find($id));
    }
    public function update($id, Request $request){
        $categoria=Categoria::find($id);
            if (!$categoria)
            return response()->json("La categoria no existe",400);
        $categoria->update($request->all());
        return $this->listarSucursal($request->input('sucursal_id'));
    }

    public function destroy ($id){
        $categoria=Categoria::find($id);
        $categoria->delete();
        return $this->listarSucursal($categoria->sucursal_id);
    }
}
