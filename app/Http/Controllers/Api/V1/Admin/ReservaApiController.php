<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Http\Resources\Admin\ReservaResource;
use App\Models\Reserva;
use App\Models\Reservation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReservaResource(Reservation::with(['establecimiento', 'cliente'])->get());
    }

    public function store(StoreReservaRequest $request)
    {
        $reserva = Reservation::create($request->all());

        return (new ReservaResource($reserva))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Reserva $reserva)
    {
        abort_if(Gate::denies('reserva_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReservaResource($reserva->load(['establecimiento', 'cliente']));
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        $reserva->update($request->all());

        return (new ReservaResource($reserva))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Reserva $reserva)
    {
        abort_if(Gate::denies('reserva_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reserva->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
