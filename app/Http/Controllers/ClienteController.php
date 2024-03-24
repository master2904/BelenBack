<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $lista=Cliente::get();
        return response()->json($lista,200);
        
    }
    public function store(Request $request)
    {
        Cliente::create($request->all());
        return $this->index();
    }
    
    public function show($id)
    {
        return response()->json(Cliente::find($id));
    }
    public function update(Request $request, $id)
    {
        $problema=Cliente::find($id);
        if (!$problema) 
            return response()->json("Este cliente no existe",400);
        $problema->update($request->all());
        return $this->index();
    }
    public function eliminar ($id,$id_p)
    {
        Cliente::find($id)->delete();
        return $this->index();
    }
    
    public function destroy($id)
    {
        $lista = Cliente::find($id);
        $lista->delete();
        return $this->index();
    }    
}
