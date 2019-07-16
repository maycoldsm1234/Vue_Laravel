<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\DetalleIngreso;
use App\Ingreso;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Solo permite realizar peticiones Ajax, si se ingresa la URL redirecciona a la ruta Raiz
        //if (!$request->ajax()) return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar=='')
        {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')->paginate(3);
        }
        else
        {
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')
            ->where('ingresos.'.$criterio , 'like', '%' . $buscar . '%')
            ->orderBy('ingresos.id','desc')->paginate(3); 
        }

        return [
            'pagination' => [
                'total'         => $ingresos->total(),
                'current_page'  => $ingresos->currentPage(),
                'per_page'      => $ingresos->perPage(),
                'last_page'     => $ingresos->lastPage(),
                'from'          => $ingresos->firstItem(),
                'to'            => $ingresos->lastItem(),
                
            ],
            'ingresos' => $ingresos
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
               
            $mytime = Carbon::now('America/Bogota'); // asignar zona horaria del pais

            $ingreso = new Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = "Registrado";
            $ingreso->save();

            $detalles = $request->data; // Recibe un Array de Detalles
            foreach ($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];  
                $detalle->save();
            }
     
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
        
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
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
        
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->condicion = 'Activado';
        $ingreso->save();
    }

}
