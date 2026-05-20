<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPagoTotemRequest;
use App\Http\Requests\StorePagoTotemRequest;
use App\Http\Requests\UpdatePagoTotemRequest;
use App\Models\ControlSesion;
use App\Models\PagoTotem;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PagoTotemController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pago_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pagoTotems = PagoTotem::with(['emisor', 'receptor', 'sesion'])->get();

        $users = User::get();

        $control_sesions = ControlSesion::get();

        return view('frontend.pagoTotems.index', compact('control_sesions', 'pagoTotems', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('pago_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.pagoTotems.create', compact('emisors', 'receptors', 'sesions'));
    }

    public function store(StorePagoTotemRequest $request)
    {
        $pagoTotem = PagoTotem::create($request->all());

        return redirect()->route('frontend.pago-totems.index');
    }

    public function edit(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pagoTotem->load('emisor', 'receptor', 'sesion');

        return view('frontend.pagoTotems.edit', compact('emisors', 'pagoTotem', 'receptors', 'sesions'));
    }

    public function update(UpdatePagoTotemRequest $request, PagoTotem $pagoTotem)
    {
        $pagoTotem->update($request->all());

        return redirect()->route('frontend.pago-totems.index');
    }

    public function show(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pagoTotem->load('emisor', 'receptor', 'sesion', 'pagoOrigenRespuestaPagos');

        return view('frontend.pagoTotems.show', compact('pagoTotem'));
    }

    public function destroy(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pagoTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyPagoTotemRequest $request)
    {
        $pagoTotems = PagoTotem::find(request('ids'));

        foreach ($pagoTotems as $pagoTotem) {
            $pagoTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
