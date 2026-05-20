<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyControlSesionRequest;
use App\Http\Requests\StoreControlSesionRequest;
use App\Http\Requests\UpdateControlSesionRequest;
use App\Models\ControlSesion;
use App\Models\Team;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlSesionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('control_sesion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesions = ControlSesion::with(['emisor', 'receptor'])->get();

        $users = User::get();

        return view('frontend.controlSesions.index', compact('controlSesions',  'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.controlSesions.create', compact('emisors', 'receptors'));
    }

    public function store(StoreControlSesionRequest $request)
    {
        // Allow concurrent control sessions for the same receptor (totem)
        // Keep single active session per emisor (user)
        $emisorId = $request->get('emisor_id');
        if ($emisorId && ControlSesion::where('estado_sesion', 1)->where('emisor_id', $emisorId)->exists()) {
            return back()->with(['message' => 'No puedes iniciar otra sesión remota hasta que cierres la actual.']);
        }

        $controlSesion = ControlSesion::create($request->all());

        return redirect()->route('frontend.control-sesions.index');
    }

    public function edit(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $controlSesion->load('emisor', 'receptor');

        return view('frontend.controlSesions.edit', compact('controlSesion', 'emisors', 'receptors'));
    }

    public function update(UpdateControlSesionRequest $request, ControlSesion $controlSesion)
    {
        $controlSesion->update($request->all());

        return redirect()->route('frontend.control-sesions.index');
    }

    public function show(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->load('emisor', 'receptor');

        return view('frontend.controlSesions.show', compact('controlSesion'));
    }

    public function destroy(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->delete();

        return back();
    }

    public function massDestroy(MassDestroyControlSesionRequest $request)
    {
        $controlSesions = ControlSesion::find(request('ids'));

        foreach ($controlSesions as $controlSesion) {
            $controlSesion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
