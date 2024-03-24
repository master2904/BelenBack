<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Proveedor;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(Item::get());
    }

    public function create()
    {
        //
    }
    public function lista($id){
        $p=Proveedor::find($id);
        $ans=[];
        $consulta=DB::select('SELECT r.id,r.id_detalle,d.codigo,concat(p.nombre," ",t.descripcion ," ", d.descripcion) as descripcion FROM products p,tipos t,detalles d,relacions r, vendors v WHERE t.id_producto=p.id and d.id_tipo=t.id and d.id=r.id_detalle and r.id_vendor=v.id and v.id=:id',['id'=>$p->id]);
        array_push($ans,$consulta);
        return response()->json($ans);

        $consulta= Item::where('id_vendor',$id)->get();
        // DB::select('select * from relacions');
        return response()->json($consulta);
    }
    public function store(Request $request)
    {
        // $form=$request->datos;
        $id=$request->id_vendor;
        // $r = new Request(array('id' =>0,'id_vendor'=>$form['id_vendor'],'id_detalle'=>$form['id_detalle']));
        Item::create($request->all());
        return $this->lista($id);
        // return $this->index();
    }

    public function show(Item $relacion)
    {
        //
    }

    public function edit(Item $relacion)
    {
        //
    }

    public function update(Request $request, Item $relacion)
    {
        //
    }

    public function destroy($id)
    {
        $r=Item::find($id);
        $id_vendor=$r->id_vendor;
        $r->delete();
        return $this->lista($id_vendor);
    }
}
