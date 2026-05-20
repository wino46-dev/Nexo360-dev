<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGrabacionTarjetumRequest;
use App\Http\Requests\StoreGrabacionTarjetumRequest;
use App\Http\Requests\UpdateGrabacionTarjetumRequest;
use App\Models\ControlSesion;
use App\Models\GrabacionTarjetum;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GrabacionTarjetaController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('grabacion_tarjetum_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjeta = GrabacionTarjetum::with(['emisor', 'receptor', 'sesion'])->get();

        $users = User::get();

        $control_sesions = ControlSesion::get();

        return view('frontend.grabacionTarjeta.index', compact('control_sesions', 'grabacionTarjeta', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('grabacion_tarjetum_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.grabacionTarjeta.create', compact('emisors', 'receptors', 'sesions'));
    }

    public function store(StoreGrabacionTarjetumRequest $request)
    {
        $grabacionTarjetum = GrabacionTarjetum::create($request->all());

        return redirect()->route('frontend.grabacion-tarjeta.index');
    }

    public function edit(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        $grabacionTarjetum->load('emisor', 'receptor', 'sesion');

        return view('frontend.grabacionTarjeta.edit', compact('emisors', 'grabacionTarjetum', 'receptors', 'sesions'));
    }

    public function update(UpdateGrabacionTarjetumRequest $request, GrabacionTarjetum $grabacionTarjetum)
    {
        $grabacionTarjetum->update($request->all());

        return redirect()->route('frontend.grabacion-tarjeta.index');
    }

    public function show(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjetum->load('emisor', 'receptor', 'sesion');

        return view('frontend.grabacionTarjeta.show', compact('grabacionTarjetum'));
    }

    public function destroy(GrabacionTarjetum $grabacionTarjetum)
    {
        abort_if(Gate::denies('grabacion_tarjetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $grabacionTarjetum->delete();

        return back();
    }

    public function massDestroy(MassDestroyGrabacionTarjetumRequest $request)
    {
        $grabacionTarjeta = GrabacionTarjetum::find(request('ids'));

        foreach ($grabacionTarjeta as $grabacionTarjetum) {
            $grabacionTarjetum->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
