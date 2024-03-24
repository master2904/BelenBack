<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Detalle;
use App\Models\Historial;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use stdClass;

class VentaController extends Controller
{
    public function index()
    {
        $lista=Venta::get();
        return response()->json($lista,200);

    }
    public function r_meses($g){
        // $a=Venta::whereYear('fecha', $g);
        $x=[];
        for($i=1;$i<13;$i++){
            // $v=Venta::whereYear('fecha', $g)->whereMonth('fecha',$i)->get();
            $consulta=DB::select('SELECT concat(p.nombre," ",t.descripcion," ",d.descripcion) as nombre, count(d.id) as valor from ventas v,historials h, detalles d, tipos t, products p WHERE d.id_tipo=t.id and t.id_producto=p.id and h.id_detalle=d.id and h.id_venta=v.id and month(v.fecha)=:mes GROUP by d.id,p.nombre,t.descripcion,d.descripcion',['mes'=> $i]);
            // $consulta=DB::select('SELECT c.nombre as "colegio",c.color,e.nombre,count(de.id_equipo) as valor from colegios c, equipos e, detalles de,categorias ca where c.codigo=e.id_colegio and e.id_categoria=ca.id and ca.id_concurso=:id and e.id=de.id_equipo GROUP BY e.id,c.nombre,c.color,e.nombre',['id'=>$id]);

            array_push($x,$consulta);
        }
        return response()->json($x);
    }
    public function listar_fecha($fecha){
        // $consulta=DB::select('SELECT v.id, u.username, c.nombre, concat(p.nombre," ",t.descripcion," ",d.descripcion) as descripcion, v.fecha FROM users u, ventas v,clientes c,detalles d, tipos t, products p WHERE v.id_usuario=u.id and v.id_detalle = d.id and v.id_cliente=c.id and d.id_tipo=t.id and t.id_producto=p.id and p.lugar=:fecha',['fecha'=> $fecha]);
        // $consulta=DB::select('SELECT v.id, u.username, c.nombre, p.nombre, t.descripcion, d.descripcion, v.cantidad,v.precio,v.sub_total, v.fecha FROM users u, ventas v,clientes c,detalles d, tipos t, products p WHERE v.id_usuario=u.id and v.id_detalle = d.id and v.id_cliente=c.id and d.id_tipo=t.id and t.id_producto=p.id',['fecha'=> $fecha]);
        return response()->json([]);
    }
    public function fecha($id,$request){
        // $listado=DB::select('SELECT v.id, v.id_usuario,v.id_cliente,v.id_detalle, sum(h.sub_total_compra) FROM ventas v,historials h where h.id_venta=v.id and v.fecha=:id',['id'=> $request]);
        $listado=DB::select('SELECT v.id, v.id_usuario,v.id_cliente FROM ventas v,historials h, detalles d, tipos t, products p where p.lugar=:lugar and p.id=t.id_producto and t.id=d.id_tipo and d.id=h.id_detalle and h.id_venta=v.id and v.fecha=:id',['id'=> $request,'lugar'=>$id]);
        // $listado=DB::select('SELECT v.id, v.id_usuario,v.id_cliente,v.total_venta,v.total_compra FROM ventas v,historials h, detalles d, tipos t, products p where p.id=t.id_producto and t.id=d.id_tipo and d.id=h.id_detalle and h.id_venta=v.id and v.fecha=:id',['id'=> $request]);
        // $listado=Venta::where('fecha',$request)->get();
        $i=0;
        $historial=[];
        while(isset($listado[$i])){
            $ans=$listado[$i];
            $listado[$i]->id_usuario=User::find($ans->id_usuario)->username;
            $listado[$i]->id_cliente=Cliente::find($ans->id_cliente)->nombre;
            $listado[$i]->total_venta=Historial::where('id_venta',$ans->id)->sum('sub_total_venta');
            $listado[$i]->total_compra=Historial::where('id_venta',$ans->id)->sum('sub_total_compra');
            $historial[$i]=Historial::where('id_venta',$ans->id)->get();
            $c=0;
            while(isset($historial[$i][$c])){
                $res=$historial[$i][$c];
                $consulta=DB::select('SELECT d.id,d.codigo,concat(p.nombre," ",t.descripcion ," ", d.descripcion) as descripcion,d.stock,d.precio_compra,d.precio_venta,d.cantidad_minima FROM products p,tipos t,detalles d WHERE t.id_producto=p.id and d.id_tipo=t.id and d.id=:id',['id'=> $res->id_detalle]);
                $historial[$i][$c]->id_detalle=$consulta[0]->descripcion;
                $c=$c+1;
            }
            $i=$i+1;
        }
        return response()->json(array($listado,$historial));
    }
    public function listar_cliente($id_cliente){
        $lista=Venta::where('id_cliente',$id_cliente)->get();
        return response()->json($lista);
    }

    // public function store(Request $request)
    // {
    //     $valor=$request->datos;
    //     $tipo_cliente=Cliente::find($valor['id_cliente'])->tipo;
    //     $productos=$request->ides;
    //     $precios=$request->precios;
    //     $cantidades=$request->cantidades;
    //     $id=Venta::create($valor)->id;
    //     $i=0;
    //     while($i<10){
    //         if($productos[$i]>0){
    //             $producto=Product::find($productos[$i]);
    //             $precio=0;
    //             if($tipo_cliente==2)
    //                 $precio=$producto->precio_final;
    //             else
    //                 $precio=$producto->precio_tienda;
    //                 $r = new Request(array(
    //                 'id' =>10,
    //                 'id_venta'=>$id,
    //                 'precio'=>$precio,
    //                 'id_producto'=>$producto->id,
    //                 'cantidad'=>$cantidades[$i],
    //                 'total'=>$cantidades[$i]*$precio
    //             ));
    //             $x=Equipo::create($r->all());
    //         }
    //         $i=$i+1;
    //     }
    //     return response()->json(array('status'=>200));
    // }


    public function store(Request $request){
        $cliente=$request->cliente;
        $historial=$request->historial;
        $venta=$request->venta;
        if($cliente['id']==0){
            $r = new Request(array('id' =>$cliente['id'],'nit'=>$cliente['nit'],'nombre'=>$cliente['nombre']));
            $r=Cliente::create($r->all());
            $venta['id_cliente']=$r->id;
        }
        $r = new Request(array('id' =>$venta['id'],'id_cliente'=>$venta['id_cliente'],'id_usuario'=>$venta['id_usuario'],'fecha'=>$venta['fecha']));
        $r=Venta::create($r->all());
        foreach($historial as $h){
            $p=Detalle::find($h['id_detalle']);
            $p->stock=$p->stock-$h['cantidad'];
            $p->save();
            // return response()->json($p);
            $h['id_venta']=$r->id;
            $ans = new Request(array(
                "id"=> 0,
                "cantidad"=> $h['cantidad'],
                "id_detalle"=> $h['id_detalle'],
                "id_venta"=> $h['id_venta'],
                "precio_compra"=> $h['precio_compra'],
                "precio_venta"=> $h['precio_venta'],
                "sub_total_compra"=> $h['sub_total_compra'],
                "sub_total_venta"=> $h['sub_total_venta'],
            ));
            Historial::create($ans->all());
        }
        $listado=Venta::where('id',$r->id)->get();
        $historial=[];
            $ans=$listado[0];
            $listado[0]->id_usuario=User::find($ans->id_usuario)->username;
            $listado[0]->nit=Cliente::find($ans->id_cliente)->nit;
            $listado[0]->nombre=Cliente::find($ans->id_cliente)->nombre;
            $listado[0]->total_venta=Historial::where('id_venta',$ans->id)->sum('sub_total_venta');
            $listado[0]->total_compra=Historial::where('id_venta',$ans->id)->sum('sub_total_compra');
            $historial[0]=Historial::where('id_venta',$ans->id)->get();
            $c=0;
            while(isset($historial[0][$c])){
                $res=$historial[0][$c];
                $consulta=DB::select('SELECT d.id,d.codigo,concat(p.nombre," ",t.descripcion ," ", d.descripcion) as descripcion,d.precio_venta FROM products p,tipos t,detalles d WHERE t.id_producto=p.id and d.id_tipo=t.id and d.id=:id',['id'=> $res->id_detalle]);
                $historial[0][$c]->id_detalle=$consulta[0]->descripcion;
                $c=$c+1;
            }
            return response()->json(array($listado,$historial));
        }
        // return response()->json(["status"=>'ok'],200);
    // }

    public function show($id)
    {
        return response()->json(Venta::find($id));
    }
    public function update(Request $request, $id)
    {
        $problema=Venta::find($id);
        if (!$problema)
            return response()->json("Este producto no existe",400);
        $problema->update($request->all());
        return $this->listar($request->input('id_product'));
    }
    public function eliminar ($id,$id_p)
    {
        Venta::find($id)->delete();
        return $this->listar($id_p);
    }

    public function delete($id)
    {
        $lista = Venta::find($id);
        $valor=$lista->id_prodcut;
        $lista->delete();
        return $this->listar($valor);
    }
}
