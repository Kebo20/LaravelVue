<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use Illuminate\Http\Request;

class DetalleIngresoController extends Controller
{
            // if (!$request->ajax()) return redirect('/');

    public function ListarxIngreso(Request $request){
        $detalle=DetalleIngreso::where("detalle_ingresos.idingreso","=",$request->idingreso)
        ->join("ingresos","ingresos.id","=","detalle_ingresos.idingreso")
        ->join("articulos","articulos.id","=","detalle_ingresos.idarticulo")

        ->select("detalle_ingresos.id","articulos.nombre","detalle_ingresos.precio","detalle_ingresos.cantidad")
        ->get();
        return $detalle;
    }
}
