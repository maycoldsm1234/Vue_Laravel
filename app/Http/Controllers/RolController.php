<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;


class RolController extends Controller
{
    
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
            $roles = Rol::orderBy('id','desc')->paginate(3);
        }
        else
        {
            $roles = Rol::where($criterio , 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(3); 
        }

        return [
            'pagination' => [
                'total'  => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'  => $roles->perPage(),
                'last_page'  => $roles->lastPage(),
                'from'  => $roles->firstItem(),
                'to'  => $roles->lastItem(),
                
            ],
            'roles' => $roles
        ];
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectRol(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $roles = Rol::where('condicion','=','1')
        ->select('id','nombre')
        ->orderBy('nombre', 'asc')->get();

        return ['roles' => $roles]; 
    }
    


}
