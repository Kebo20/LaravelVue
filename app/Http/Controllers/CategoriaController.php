<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{

    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');
        // $categorias = Categoria::all();
        if ($criterio != '') {
            $categorias = Categoria::where($criterio, 'like', "%" . $buscar . "%")->orderBy('id', 'desc')->paginate(2); //ELOQUENT

        } else {
            $categorias = Categoria::orderBy('id', 'desc')->paginate(2); //ELOQUENT
        }

        //$categorias = DB::table('categorias')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [

                'total' => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page' => $categorias->perPage(),
                'last_page' => $categorias->lastPage(),
                'from' => $categorias->firstItem(),
                'to' => $categorias->lastItem(),
            ],
            'categorias' => $categorias

        ];
    }


    public function listar(Request $request)
    {


        $categorias = Categoria::all(); //ELOQUENT

        return $categorias;
    }




    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria =  Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria =  Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}
