<?php

namespace App;
use App\Proveedor;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [
        'idproveedor', 'idusuario', 'tipo_comprobante', 'serie_comprobante', 'num_comprobante',
        'fecha_hora', 'impuesto', 'total', 'estado'
    ];


    public function proveedor(){
       return $this->belongsTo('Proveedor','idproveedor');
    }

    public function usuario(){
        return $this->belongsTo('User','idusuario');
    }
    
}
