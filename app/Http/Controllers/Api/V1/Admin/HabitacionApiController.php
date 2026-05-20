<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHabitacionRequest;
use App\Http\Requests\UpdateHabitacionRequest;
use App\Http\Resources\Admin\HabitacionResource;
use App\Models\Habitacion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HabitacionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('habitacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HabitacionResource(Habitacion::with(['establecimiento'])->get());
    }

    public function store(StoreHabitacionRequest $request)
    {
        $habitacion = Habitacion::create($request->all());

        return (new HabitacionResource($habitacion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new HabitacionResource($habitacion->load(['establecimiento']));
    }

    public function update(UpdateHabitacionRequest $request, Habitacion $habitacion)
    {
        $habitacion->update($request->all());

        return (new HabitacionResource($habitacion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $habitacion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
