<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Articulo;

class Categoria extends Model
{
    // asociamos que la var $table es la tabla categorias
    //protected $table = 'categorias';
    //protected $primaryKey = 'id';
    protected $fillable = ['nombre','descripcion','condicion'];
    
    public function articulos()
    {
        return $this->hasMany(Articulo::class);
    }
}
