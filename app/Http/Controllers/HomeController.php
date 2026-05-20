<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
	

		$usuario = User::where('id',Auth()->user()->id)->first();
		if(isset($usuario->id)){
			if($usuario->internalUser() == 1){
				return redirect('/admin');
			}else{
				return view('home');
			}
		}

    }
}
