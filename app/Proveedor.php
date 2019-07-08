<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Proveedor extends Model
{
    // Nombre de la tabla a la que hace referencia Este Modelo
    protected $table = 'proveedores';

    protected $fillable = ['id','contacto','telefono_contacto'];
    
    //
    public $timestamps = false;

    //
    public function persona()
    {
        return $this->belongsTo(User::class);
    }
}
