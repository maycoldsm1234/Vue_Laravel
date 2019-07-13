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
        $this->validateLogin($request);

        // Verificamos que los datos ingresados para autenticación sean correctos, adicional que su 
        // Condicion sea = 1, lo que implica que existe
        if(Auth::attempt(['usuario'=> $request->usuario, 'password'=> $request->password, 'condicion'=> 1]))
        {
            // En caso que exista, lo redireccionamos a la vista main
            return redirect()->route('main');
        }
        

        return back()
        ->withErrors(['usuario' => trans('auth.failed')])
        ->withInput(request(['usuario']));

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
