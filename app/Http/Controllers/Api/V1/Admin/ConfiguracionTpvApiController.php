<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionTpvRequest;
use App\Http\Requests\UpdateConfiguracionTpvRequest;
use App\Http\Resources\Admin\ConfiguracionTpvResource;
use App\Models\ConfiguracionTpv;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionTpvApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_tpv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionTpvResource(ConfiguracionTpv::with(['totem'])->get());
    }

    public function store(StoreConfiguracionTpvRequest $request)
    {
        $configuracionTpv = ConfiguracionTpv::create($request->all());

        return (new ConfiguracionTpvResource($configuracionTpv))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ConfiguracionTpv $configuracionTpv)
    {
        abort_if(Gate::denies('configuracion_tpv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionTpvResource($configuracionTpv->load(['totem']));
    }

    public function update(UpdateConfiguracionTpvRequest $request, ConfiguracionTpv $configuracionTpv)
    {
        $configuracionTpv->update($request->all());

        return (new ConfiguracionTpvResource($configuracionTpv))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ConfiguracionTpv $configuracionTpv)
    {
        abort_if(Gate::denies('configuracion_tpv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionTpv->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
