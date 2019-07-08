<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Persona;
use App\User;

class UserController extends Controller
{
    //
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Solo permite realizar peticiones Ajax, si se ingresa la URL redirecciona a la ruta Raiz
        // if (!$request->ajax()) return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar=='')
        {
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password','users.condicion',
            'users.idrol','roles.nombre as rol')
            ->orderBy('personas.id','desc')->paginate(3);
        }
        else
        {
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password','users.condicion',
            'users.idrol','roles.nombre as rol')
            ->where('personas.'.$criterio , 'like', '%' . $buscar . '%')
            ->orderBy('id','desc')->paginate(3); 
        }

        return [
            'pagination' => [
                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem(),
                
            ],
            'personas' => $personas
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
        
        try
        {
            // $proveedor->contacto = $request->input('');

            DB::beginTransaction();
                
            $persona = new Persona();
            $persona->nombre = $request->input('nombre');
            $persona->tipo_documento = $request->input('tipo_documento');
            $persona->num_documento = $request->input('num_documento');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->email = $request->input('email');
            
            $persona->save();

            $user = new User();
            $user->usuario = $request->input('usuario');
            $user->password = bcrypt($request->input('password'));
            $user->condicion = '1';
            $user->idrol = $request->input('idrol');        
            $user->id = $persona->id;
            
            $user->save();
            // Ejecuta Los cambios en la db.
            DB::commit();
         
        } catch (Exception $error)
        {
            // En caso tal de algún error, desace la transacción
            DB::rollBack();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
    
        try
        {
            DB::beginTransaction();
                
            // Primero Busca El ID que dea modificar en ambas tablas
            $user = User::findOrFail($request->id);

            $persona = Persona::findOrFail($user->id);


           
            // Ejecuta las peticiones, recolectando los datos del formulario tabla Personas
            $persona->nombre = $request->input('nombre');
            $persona->tipo_documento = $request->input('tipo_documento');
            $persona->num_documento = $request->input('num_documento');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->email = $request->input('email');
            
            $persona->save();

            
            // Ejecuta las peticiones, recolectando los datos del formulario tabla Proveedor
            $user->usuario = $request->input('usuario');
            $user->password = bcrypt($request->input('password'));
            $user->condicion = '1';
            $user->idrol = $request->idrol;

            $user->save();
            // Ejecuta Los cambios en la db.
            DB::commit();
        
        } catch (Exception $error)
        {
            // En caso tal de algún error, desace la transacción
            DB::rollBack();
        }
    }

    /**
     * desactived the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
        
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
        
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

}
