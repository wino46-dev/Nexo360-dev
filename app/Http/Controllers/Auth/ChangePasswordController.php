<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        abort_if(Gate::denies('profile_password_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('auth.passwords.edit');
    }

    public function update(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        // Profile editing is disabled in this installation
        abort(403, 'Profile editing is disabled.');
    }

    public function destroy()
    {
        // Account deletion is disabled in this installation
        abort(403, 'Account deletion is disabled.');
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = auth()->user();

        if ($user->two_factor) {
            $message = __('global.two_factor.disabled');
        } else {
            $message = __('global.two_factor.enabled');
        }

        $user->two_factor = ! $user->two_factor;

        $user->save();

        return redirect()->route('profile.password.edit')->with('message', $message);
    }

    public function updatePmsPassword(Request $request)
    {
        $data = $request->all();

        auth()->user()->update([
            'pms_password' => $data['pms_password']
        ]);

        return redirect()->route('profile.password.edit')->with('message', 'Password Pms Actualizado');

    }
}
