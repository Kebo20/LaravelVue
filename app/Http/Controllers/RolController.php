<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
   

    public function index(Request $request)
    {

        $buscar = $request->buscar;
         if (!$request->ajax()) return redirect('/');
            $rol = Rol::select(
                "id",
                "nombre",
                "descripcion",
                "condicional"
                
            )
                ->where("nombre", 'like', "%" . $buscar . "%")->orderBy('id', 'desc')->paginate(2); //ELOQUENT ORM

        //where($criterio, 'like', "%" . $buscar . "%")->
        return [
            'pagination' => [

                'total' => $rol->total(),
                'current_page' => $rol->currentPage(),
                'per_page' => $rol->perPage(),
                'last_page' => $rol->lastPage(),
                'from' => $rol->firstItem(),
                'to' => $rol->lastItem(),
            ],
            'roles' => $rol

        ];
    }


}
