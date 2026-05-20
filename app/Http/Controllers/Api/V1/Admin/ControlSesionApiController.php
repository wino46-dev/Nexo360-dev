<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreControlSesionRequest;
use App\Http\Requests\UpdateControlSesionRequest;
use App\Http\Resources\Admin\ControlSesionResource;
use App\Models\ControlSesion;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlSesionApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('control_sesion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ControlSesionResource(ControlSesion::with(['emisor', 'receptor'])->get());
    }

    public function store(StoreControlSesionRequest $request)
    {
        // Block concurrent control sessions for the same receptor
        $receptorId = $request->get('receptor_id');
        if (ControlSesion::where('estado_sesion', 1)->where('receptor_id', $receptorId)->exists()) {
            return response([ 'message' => 'El tótem ya tiene una sesión de control activa' ], Response::HTTP_CONFLICT);
        }
        // Optional: block multiple active sessions for the same emisor
        $emisorId = $request->get('emisor_id');
        if ($emisorId && ControlSesion::where('estado_sesion', 1)->where('emisor_id', $emisorId)->exists()) {
            return response([ 'message' => 'El emisor ya tiene una sesión de control activa' ], Response::HTTP_CONFLICT);
        }

        $controlSesion = ControlSesion::create($request->all());

        return (new ControlSesionResource($controlSesion))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ControlSesionResource($controlSesion->load(['emisor', 'receptor']));
    }

    public function update(UpdateControlSesionRequest $request, ControlSesion $controlSesion)
    {
        $controlSesion->update($request->all());

        return (new ControlSesionResource($controlSesion))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
