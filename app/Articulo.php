<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Articulo extends Model
{
    protected $fillable = ['idcategoria', 'codigo', 'nombre', 'precio_venta', 'stock', 'condicion', 'descripcion'];
    public function categoria()
    {
        return $this->belongsTo('App\Categoria',"idcategoria");
    }
}
