<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LoginRedirect extends Controller
{
	public function index(){
        $usuario = User::where('id', auth()->id())->first();
        if (isset($usuario->id)) {
            // Redirect based on user type
            if ($usuario->isTotem()) {
                return redirect('/home'); // frontend
            }
            if ($usuario->isExternal()) {
                return redirect('/external');
            }
            // default internal
            return redirect('/admin');
        }
	}

}
