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
        $items=Item::where('proveedor_id',$id)->get();
        return response()->json($items);
    }
    public function store(Request $request)
    {
        $request['producto_id']=($request['producto_id']==0?null:$request['producto_id']);
        $item=Item::create($request->all());
        return $this->lista($item->proveedor_id);
    }

    public function show(Item $relacion)
    {
        //
    }

    public function edit(Item $relacion)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $item=Item::find($id);
        $params=$request->all();
        $item->update($params);
        return $this->lista($item->proveedor_id);
    }

    public function destroy($id)
    {
        $r=Item::find($id);
        $id=$r->proveedor_id;
        $r->delete();
        return $this->lista($id);
    }
}
