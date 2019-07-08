<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Proveedor;
use App\Persona;



class ProveedorController extends Controller
{
    //
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
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id','desc')->paginate(3);
        }
        else
        {
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id','desc')
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

            $proveedor = new Proveedor();
            $proveedor->contacto = $request->input('contacto');
            $proveedor->telefono_contacto = $request->input('telefono_contacto');
            $proveedor->id = $persona->id;
            
            $proveedor->save();
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
            $proveedor = Proveedor::findOrFail($request->id);

            $persona = Persona::findOrFail($proveedor->id);

            // Ejecuta las peticiones, recolectando los datos del formulario tabla Personas
            $persona->nombre = $request->input('nombre');
            $persona->tipo_documento = $request->input('tipo_documento');
            $persona->num_documento = $request->input('num_documento');
            $persona->direccion = $request->input('direccion');
            $persona->telefono = $request->input('telefono');
            $persona->email = $request->input('email');
            
            $persona->save();

            
            // Ejecuta las peticiones, recolectando los datos del formulario tabla Proveedor
            $proveedor->contacto = $request->input('contacto');
            $proveedor->telefono_contacto = $request->input('telefono_contacto');
            
            $proveedor->save();
            // Ejecuta Los cambios en la db.
            DB::commit();
        
        } catch (Exception $error)
        {
            // En caso tal de algún error, desace la transacción
            DB::rollBack();
        }
    }

}
