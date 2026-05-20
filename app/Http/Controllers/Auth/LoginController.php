<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Notifications\TwoFactorCodeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        $user = auth()->user();
        // Totem users should not go to admin; handle regardless of case and role capitalization
        if ($user && method_exists($user, 'isTotem') && $user->isTotem()) {
            return '/home';
        }
        if ($user && $user->internalUser()) {
            return '/admin';
        }
        return '/home';
    }

protected function authenticated(Request $request, $user)
{
    $close_old_sessions =  Session::where('user_id',auth()->user()->id)
                    ->orderBy('last_activity','desc')
                    ->skip(1)
                    ->delete();

    if ($user->two_factor) {
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCodeNotification());
    }
}
}
