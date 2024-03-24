<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        return $this->listar();
        // $product = Vendor::select("*")->orderBy("nombre", "asc")->get();
        // return response()->json($product,200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'empresa'=>'required',
            'celular'=>'required'
            // 'observacion'=>'required'
        ]);
        Proveedor::create($request->all());
        return $this->listar();
    }

    public function show($id)
    {
        return response()->json(Proveedor::find($id));
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
        $ans=[];
        foreach($proveedores as $p){
            $consulta=DB::select('SELECT r.id,r.id_detalle,d.codigo,concat(p.nombre," ",t.descripcion ," ", d.descripcion) as descripcion FROM products p,tipos t,detalles d,relacions r, vendors v WHERE t.id_producto=p.id and d.id_tipo=t.id and d.id=r.id_detalle and r.id_vendor=v.id and v.id=:id',['id'=>$p->id]);
            array_push($ans,$consulta);
        }
        return response()->json(array($proveedores,$ans));
    }
    public function destroy($id)
    {
        Proveedor::find($id)->delete();
        return $this->index();
    }
}
