<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Proveedor;
use App\Persona;
use Exception;

class ProveedorController extends Controller
{

    public function selectProveedor(Request $request)
    {
        
        if (!$request->ajax()) return redirect('/');
        $filtro=$request->filtro;
        $proveedor = Proveedor::join("personas", "personas.id", "=", "proveedores.id")->select(
            "personas.id",
            "personas.nombre",
            "personas.tipo_documento",
            "personas.num_documento",
            "personas.email",
            "personas.telefono",
            "personas.direccion",
            "proveedores.contacto",
            "proveedores.telefono_contacto"
        )->where("personas.nombre", 'like', "%" . $filtro . "%")->orderBy('personas.id', 'desc')->get();
        return $proveedor;
    }

    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');
        // $proveedor = Proveedor::all();
        if ($criterio != '') {
            $proveedor = Proveedor::join("personas", "personas.id", "=", "proveedores.id")->select(
                "personas.id",
                "personas.nombre",
                "personas.tipo_documento",
                "personas.num_documento",
                "personas.email",
                "personas.telefono",
                "personas.direccion",
                "proveedores.contacto",
                "proveedores.telefono_contacto"
            )
                ->where("personas." . $criterio, 'like', "%" . $buscar . "%")->orderBy('personas.id', 'desc')->paginate(2); //ELOQUENT ORM

        } else {
            $proveedor = Proveedor::join("personas", "personas.id", "=", "proveedores.id")->select(
                "personas.id",
                "personas.nombre",
                "personas.tipo_documento",
                "personas.num_documento",
                "personas.email",
                "personas.telefono",
                "personas.direccion",
                "proveedores.contacto",
                "proveedores.telefono_contacto"
            )
                ->orderBy('personas.id', 'desc')->paginate(2); //ELOQUENT
        }

        //$proveedor = DB::table('proveedor')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [

                'total' => $proveedor->total(),
                'current_page' => $proveedor->currentPage(),
                'per_page' => $proveedor->perPage(),
                'last_page' => $proveedor->lastPage(),
                'from' => $proveedor->firstItem(),
                'to' => $proveedor->lastItem(),
            ],
            'proveedores' => $proveedor

        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $persona = new Persona();
            $persona->tipo_documento = $request->tipo_documento;
            $persona->nombre = $request->nombre;
            $persona->num_documento = $request->num_documento;
            $persona->email = $request->email;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();
            return $persona;
        } catch (Exception $ex) {

            DB::rollBack();
            return $ex;
        }
    }


    public function update(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();

            $proveedor = Proveedor::findOrFail($request->id);

            $persona = Persona::findOrFail($proveedor->id);

            $persona->tipo_documento = $request->tipo_documento;
            $persona->nombre = $request->nombre;
            $persona->num_documento = $request->num_documento;
            $persona->email = $request->email;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;
            $persona->save();


            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();

            DB::commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }
    }
}
