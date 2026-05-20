<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionGrabadorRequest;
use App\Http\Requests\UpdateConfiguracionGrabadorRequest;
use App\Http\Resources\Admin\ConfiguracionGrabadorResource;
use App\Models\ConfiguracionGrabador;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionGrabadorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_grabador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionGrabadorResource(ConfiguracionGrabador::with(['totem'])->get());
    }

    public function store(StoreConfiguracionGrabadorRequest $request)
    {
        $configuracionGrabador = ConfiguracionGrabador::create($request->all());

        return (new ConfiguracionGrabadorResource($configuracionGrabador))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionGrabadorResource($configuracionGrabador->load(['totem']));
    }

    public function update(UpdateConfiguracionGrabadorRequest $request, ConfiguracionGrabador $configuracionGrabador)
    {
        $configuracionGrabador->update($request->all());

        return (new ConfiguracionGrabadorResource($configuracionGrabador))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionGrabador->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
