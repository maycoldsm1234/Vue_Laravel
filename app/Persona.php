<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Proveedor;
use App\User;


class Persona extends Model
{
    //
    protected $fillable = [
        'nombre','tipo_documento','num_documento',
        'direccion','telefono','email'];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
