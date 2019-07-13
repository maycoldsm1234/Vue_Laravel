<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Verifica que los datos si existan en la db.
        $this->validateLogin($request);

        // Verificamos que los datos ingresados para autenticación sean correctos, adicional que su 
        // Condicion sea = 1, lo que implica que existe
        if(Auth::attempt(['usuario'=> $request->usuario, 'password'=> $request->password, 'condicion'=> 1]))
        {
            // En caso que exista, lo redireccionamos a la vista main
            return redirect()->route('main');
        }
        

        return back()
        ->withErrors(['usuario' => trans('auth.failed')]) // En caso de algun error, invoca el metodo failed en Resources/Langs/auth
        ->withInput(request(['usuario']));  // En caso tal que el usuario exista, pero con credenciales invalidas... 
                                            // retorna el usuario al formulario login por medio de la propiedad value="{{ old('usuario') }}"

    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, 
        [
            'usuario' => 'required|string',
            'password' => 'required|string',
            
        ]);
    }

}
