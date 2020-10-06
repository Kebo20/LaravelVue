<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class PersonaController extends Controller
{
    
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');
        // $personas = persona::all();
        if ($criterio != '') {
            $personas = Persona::select("personas.id","personas.nombre","personas.tipo_documento",
            "personas.num_documento","personas.email","personas.telefono","personas.direccion")
            ->where("personas.".$criterio,'like',"%".$buscar."%")->orderBy('id','desc')->paginate(2); //ELOQUENT

        } else {
            $personas = Persona::select("personas.id","personas.nombre","personas.tipo_documento",
            "personas.num_documento","personas.email","personas.telefono","personas.direccion")
            ->orderBy('id','desc')->paginate(2); //ELOQUENT
        }

        //$personas = DB::table('personas')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [

                'total' => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page' => $personas->perPage(),
                'last_page' => $personas->lastPage(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem(),
            ],
            'personas' => $personas

        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
      
        $persona->tipo_documento= $request->tipo_documento;
        $persona->nombre = $request->nombre;
        $persona->num_documento=$request->num_documento;
        $persona->email= $request->email;
        $persona->telefono = $request->telefono;
        $persona->direccion=$request->direccion;
        $persona->save();
    }


    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona =  Persona::findOrFail($request->id);
        $persona->tipo_documento= $request->tipo_documento;
        $persona->nombre = $request->nombre;
        $persona->num_documento=$request->num_documento;
        $persona->email= $request->email;
        $persona->telefono = $request->telefono;
        $persona->direccion=$request->direccion;
        $persona->save();
    }


  
    
}
