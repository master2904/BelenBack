<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Proveedor;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        return $this->listar();
    }

    public function store(Request $request)
    {
        $ans=$request->all();
        $ans['observacion']=($ans['observacion']==null?" ":$ans['observacion']);
        Proveedor::create($ans);
        return $this->listar();
    }

    public function show($id)
    {
        $item=Proveedor::find($id);
        $items=Item::where('proveedor_id',$item->id)->get();
        $item->items=$items;
        return response()->json($item);
    }
    public function update(Request $request, $id)
    {
        $producto=Proveedor::find($id);
        if (!$producto)
            return response()->json("Este Proveedor no existe",400);
        $producto->update($request->all());
        return $this->listar();
    }
    public function listar(){
        $proveedores=Proveedor::get();
        foreach($proveedores as $item){
            $consulta=Item::ItemProveedor($item->id);
            $item->detalle=$consulta;
        }
        return response()->json($proveedores);
    }
    public function destroy($id)
    {
        Proveedor::find($id)->delete();
        return $this->index();
    }
}
