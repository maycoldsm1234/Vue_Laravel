<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\categoria;

class Articulo extends Model
{
    // Vector donde se declara los datos que van a recibir cambios externos desde el formulario
    protected $fillable = ['idcategoria','codigo','nombre','precio_venta','stock','descripcion','condicion'];

    public function categoria(){
        return $this->belongsTo(categoria::class);
    }
}
