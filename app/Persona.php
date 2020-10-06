<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre','telefono','email',
    'direcion','tipo_documento','num_documento'];

    public function proveedor(){
        return $this->hasOne('App/Proveedor');
    }

    public function usuarios(){
        return $this->hasMany('App/User');
    }
}
