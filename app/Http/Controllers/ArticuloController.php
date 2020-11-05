<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;
use App\Proveedor;
use App\Persona;
use App\Categoria;


class ArticuloController extends Controller
{
    public function all($id){
        $lista=Articulo::all();
        return $lista;
    
    }
    public function categoria($id){
      
        return Categoria::find($id)->articulos;

    }

    public function actualizar($id){
        Articulo::where("id",$id)->update(["stock"=>200]);
    }

    public function crear(){
       $insertar= Articulo::insert([["idcategoria"=>1,"codigo"=>"001","nombre"=>"queso edam","precio_venta"=>5.5,"descripcion"=>"producto lacteo","condicion"=>"1","stock"=>30],
        ["idcategoria"=>1,"codigo"=>"002","nombre"=>"queso chedart","precio_venta"=>6.5,"descripcion"=>"producto lacteo","condicion"=>"1","stock"=>25]]);
    if($insertar){
        return "ok";
    }else{
        return "error";
    }
    }
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');
        // $articulos = articulo::all();
        if ($criterio != '') {
            $articulos = Articulo::join("categorias","articulos.idcategoria","=","categorias.id")
            ->select("articulos.id","articulos.idcategoria","articulos.codigo","articulos.nombre","articulos.condicion",
            "articulos.descripcion","categorias.nombre as nombre_categoria","articulos.stock","articulos.precio_venta")
            ->where("articulos.".$criterio,'like',"%".$buscar."%")->orderBy('id','desc')->paginate(2); //ELOQUENT

        } else {
            $articulos = Articulo::join("categorias","articulos.idcategoria","=","categorias.id")
            ->select("articulos.id","articulos.idcategoria","articulos.codigo","articulos.nombre","articulos.condicion",
            "articulos.descripcion","categorias.nombre as nombre_categoria","articulos.stock","articulos.precio_venta")
            ->orderBy('id','desc')->paginate(2); //ELOQUENT
        }

        //$articulos = DB::table('articulos')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [
                'total' => $articulos->total(),
                'current_page' => $articulos->currentPage(),
                'per_page' => $articulos->perPage(),
                'last_page' => $articulos->lastPage(),
                'from' => $articulos->firstItem(),
                'to' => $articulos->lastItem(),
            ],
            'articulos' => $articulos
        ];
    }

    public function buscarArticulo(Request $request){
        if (!$request->ajax()) return redirect('/');
        $filtro=$request->filtro;

        $articulo=Articulo::where('codigo','=',$filtro)->select('id','nombre')->orderBy('nombre','asc')
        ->take(1)->get();

        return $articulo;

    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta=$request->precio_venta;
        $articulo->stock= $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo =  Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta=$request->precio_venta;
        $articulo->stock= $request->stock;
        $articulo->descripcion = $request->descripcion;
        $articulo->condicion = '1';
        $articulo->save();
    }


    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findOrFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo =  Articulo::findOrFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }

    
}
