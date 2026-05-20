<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReservaRequest;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Cliente;
use App\Models\Establecimiento;
use App\Models\Reserva;
use App\Models\Reservation;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservaController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$reservas = Reservation::with(['establecimiento', 'cliente'])->get();

        //$establecimientos = Establecimiento::get();

        //$clientes = Cliente::get();

        //return view('frontend.reservas.index', compact('clientes', 'establecimientos', 'reservas'));
        return view('frontend.reservas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('reserva_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.reservas.create', compact('clientes', 'establecimientos'));
    }

    public function store(StoreReservaRequest $request)
    {
        $reserva = Reservation::create($request->all());

        return redirect()->route('frontend.reservas.index');
    }

    public function edit(Reserva $reserva)
    {
        abort_if(Gate::denies('reserva_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $reserva->load('establecimiento', 'cliente');

        return view('frontend.reservas.edit', compact('clientes', 'establecimientos', 'reserva'));
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        $reserva->update($request->all());

        return redirect()->route('frontend.reservas.index');
    }

    public function show(Reserva $reserva)
    {
        abort_if(Gate::denies('reserva_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reserva->load('establecimiento', 'cliente');

        return view('frontend.reservas.show', compact('reserva'));
    }

    public function destroy(Reserva $reserva)
    {
        abort_if(Gate::denies('reserva_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reserva->delete();

        return back();
    }

    public function massDestroy(MassDestroyReservaRequest $request)
    {
        $reservas = Reservation::find(request('ids'));

        foreach ($reservas as $reserva) {
            $reserva->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
