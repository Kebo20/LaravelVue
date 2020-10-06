<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Exception;



class UserController extends Controller
{
    
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');
        // $User = User::all();
        if ($criterio != '') {
            $User = User::join("personas", "personas.id", "=", "users.id")
            ->join("roles", "roles.id", "=", "users.idrol")
            
            ->select(
                "personas.id",
                "personas.nombre",
                "personas.tipo_documento",
                "personas.num_documento",
                "personas.email",
                "personas.telefono",
                "personas.direccion",
                "users.usuario",
                "users.password",
                "users.condicion",
                "users.idrol",
                "roles.nombre as rol"
            )
                ->where("personas." . $criterio, 'like', "%" . $buscar . "%")->orderBy('personas.id', 'desc')->paginate(2); //ELOQUENT ORM

        } else {
            $User = User::join("personas", "personas.id", "=", "users.id")
            ->join("roles", "roles.id", "=", "users.idrol")
            
            ->select(
                "personas.id",
                "personas.nombre",
                "personas.tipo_documento",
                "personas.num_documento",
                "personas.email",
                "personas.telefono",
                "personas.direccion",
                "users.usuario",
                "users.password",
                "users.condicion",
                "users.idrol",
                "roles.nombre as rol"
            )
                ->orderBy('personas.id', 'desc')->paginate(2); //ELOQUENT
        }

        //$User = DB::table('User')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [

                'total' => $User->total(),
                'current_page' => $User->currentPage(),
                'per_page' => $User->perPage(),
                'last_page' => $User->lastPage(),
                'from' => $User->firstItem(),
                'to' => $User->lastItem(),
            ],
            'User' => $User

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

            $user = new User();
            $user->usuario = $request->usuario;
            $user->password =bcrypt($request->password);
            $user->idrol = $request->idrol;

            $user->id = $persona->id;
            $user->save();

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

            $user = User::findOrFail($request->id);

            $persona = Persona::findOrFail($user->id);

            $persona->tipo_documento = $request->tipo_documento;
            $persona->nombre = $request->nombre;
            $persona->num_documento = $request->num_documento;
            $persona->email = $request->email;
            $persona->telefono = $request->telefono;
            $persona->direccion = $request->direccion;
            $persona->save();

            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->idrol = $request->idrol;
            $user->id = $persona->id;
            $user->condicion = '1';
            $user->save();
            
            DB::commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user =  User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
}
