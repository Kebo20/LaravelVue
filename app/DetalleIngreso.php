<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $fillable = [
        'idingreso', 'idarticulo', 'cantidad', 'precio'
    ];

    protected $table = 'detalle_ingresos';

    public $timestamps=false;
    public function ingreso()
    {
        return $this->belongsTo('App/Ingreso', 'idingreso');
    }

    public function articulo()
    {
        return $this->belongsTo('App/Articulo', 'idarticulo');
    }
}
