<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Proveedor extends Model
{
    protected $table='proveedores'; //DECLARAR NOMBRE DE LA TABLA EN BD
    protected $fillable = ['id','contacto','telefono_contacto'];
    public $timestamps=false;

    public function persona(){
        return $this->belongsTo('App/Persona');
    }
}
