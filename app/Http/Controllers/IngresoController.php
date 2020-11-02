<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Ingreso;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngresoController extends Controller
{
    public function index(Request $request)
    {

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        // if (!$request->ajax()) return redirect('/');

        if ($criterio != '') {
            $Ingreso = Ingreso::join("proveedores", "proveedores.id", "=", "ingresos.idproveedor")
                ->join("users", "users.id", "=", "ingresos.idusuario")
                ->join("personas", "proveedores.id", "=", "personas.id")
                ->join("roles", "roles.id", "=", "users.idrol")
                ->select(
                    "ingresos.id",
                    "ingresos.tipo_comprobante",
                    "ingresos.serie_comprobante",

                    "ingresos.num_comprobante",
                    "ingresos.fecha_hora",
                    "ingresos.impuesto",
                    "ingresos.total",
                    "ingresos.estado",
                    "personas.nombre as proveedor",
                    "users.usuario",
                    "roles.nombre as rol"
                )
                ->where("ingresos." . $criterio, 'like', "%" . $buscar . "%")->orderBy('ingresos.id', 'desc')->paginate(2); //ELOQUENT ORM

        } else {
            $Ingreso = Ingreso::join("proveedores", "proveedores.id", "=", "ingresos.idproveedor")
                ->join("users", "users.id", "=", "ingresos.idusuario")
                ->join("personas", "proveedores.id", "=", "personas.id")
                ->join("roles", "roles.id", "=", "users.idrol")
                ->select(
                    "ingresos.id",
                    "ingresos.tipo_comprobante",
                    "ingresos.serie_comprobante",

                    "ingresos.num_comprobante",
                    "ingresos.fecha_hora",
                    "ingresos.impuesto",
                    "ingresos.total",
                    "ingresos.estado",
                    "personas.nombre as proveedor",
                    "users.usuario",
                    "roles.nombre as rol"
                )
                ->orderBy('ingresos.id', 'desc')->paginate(2); //ELOQUENT ORM
        }

        //$User = DB::table('User')->paginate(2);// QUERY BUILDER
        return [
            'pagination' => [

                'total' => $Ingreso->total(),
                'current_page' => $Ingreso->currentPage(),
                'per_page' => $Ingreso->perPage(),
                'last_page' => $Ingreso->lastPage(),
                'from' => $Ingreso->firstItem(),
                'to' => $Ingreso->lastItem(),
            ],
            'ingresos' => $Ingreso

        ];
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $time = Carbon::now('America/Lima');
            $ingreso = new Ingreso();
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $time->toDateString();
            $ingreso->estado = "registrado";
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = Auth::user()->id;
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;

            $ingreso->save();
            $detalles = $request->detalles;

            foreach ($detalles as $d) {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $d['idarticulo'];
                $detalle->cantidad = $d['cantidad'];
                $detalle->precio = $d['precio'];
                $detalle->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'anulado';
        $ingreso->save();
    }
}
