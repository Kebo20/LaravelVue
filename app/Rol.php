<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='roles'; //DECLARAR NOMBRE DE LA TABLE EN BD
    protected $fillable = ['nombre','descripcion','condicion'];
    public $timestamps=false;
    
    public function usuarios(){
        return $this->hasMany('App/User');
    }
}
