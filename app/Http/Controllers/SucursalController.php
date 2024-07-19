<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Http\Requests\StoreSucursalRequest;
use App\Http\Requests\UpdateSucursalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SucursalController extends Controller
{
    public function index(){
        return response()->json(Sucursal::get(),200);
    }
    public function store(StoreSucursalRequest $request)
    {
        $sucursal=Sucursal::create($request->all());
        return $this->index();
    }

    public function show(Sucursal $sucursal)
    {
        $sucursal=Sucursal::find($sucursal)->first();
        return response()->json($sucursal);
    }
    public function update(UpdateSucursalRequest $request, Sucursal $sucursal)
    {
        $sucursal=Sucursal::find($sucursal['id']);
        $request['imagen']=($request['imagen']==null?$sucursal->imagen:$request['imagen']);
        $sucursal->update($request->all());
        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursal $sucursal)
    {
        $sucursal=Sucursal::find($sucursal['id']);
        $sucursal->delete();
        return $this->index();
    }
    public function imageUpload(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);
        $imagen=$request->file('image');
        $path_img='sucursal';
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
        return response()->download(public_path('storage').'/sucursal/'.$nombre,$nombre);
    }

}
