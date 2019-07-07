<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Solo permite realizar peticiones Ajax, si se ingresa la URL redirecciona a la ruta Raiz
        if (!$request->ajax()) return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar=='')
        {
            $categorias = Categoria::orderBy('id','desc')->paginate(3);
        }
        else
        {
            $categorias = Categoria::where($criterio , 'like', '%' . $buscar . '%')->orderBy('id','desc')->paginate(3); 
        }

        return [
            'pagination' => [
                'total'  => $categorias->total(),
                'current_page'  => $categorias->currentPage(),
                'per_page'  => $categorias->perPage(),
                'last_page'  => $categorias->lastPage(),
                'from'  => $categorias->firstItem(),
                'to'  => $categorias->lastItem(),
                
            ],
            'categorias' => $categorias
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
        
        $categorias = new Categoria();
        $categorias->nombre = $request->input('nombre');
        $categorias->descripcion = $request->input('descripcion');
        $categorias->condicion = '1';
        $categorias->save();
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
       
        $categorias = Categoria::findOrFail($request->id);
        $categorias->nombre = $request->input('nombre');
        $categorias->descripcion = $request->input('descripcion');
        $categorias->condicion = '1';
        $categorias->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
        
        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '0';
        $categorias->save();
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
        
        $categorias = Categoria::findOrFail($request->id);
        $categorias->condicion = '1';
        $categorias->save();
    }
}
