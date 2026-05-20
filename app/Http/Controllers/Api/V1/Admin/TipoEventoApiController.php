<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Http\Resources\Admin\TipoEventoResource;
use App\Models\TipoEvento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoEventoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tipo_evento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TipoEventoResource(TipoEvento::with(['team'])->get());
    }

    public function store(StoreTipoEventoRequest $request)
    {
        $tipoEvento = TipoEvento::create($request->all());

        return (new TipoEventoResource($tipoEvento))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TipoEventoResource($tipoEvento->load(['team']));
    }

    public function update(UpdateTipoEventoRequest $request, TipoEvento $tipoEvento)
    {
        $tipoEvento->update($request->all());

        return (new TipoEventoResource($tipoEvento))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoEvento->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
