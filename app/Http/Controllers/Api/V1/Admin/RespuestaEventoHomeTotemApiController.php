<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRespuestaEventoHomeTotemRequest;
use App\Http\Requests\UpdateRespuestaEventoHomeTotemRequest;
use App\Http\Resources\Admin\RespuestaEventoHomeTotemResource;
use App\Models\RespuestaEventoHomeTotem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RespuestaEventoHomeTotemApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespuestaEventoHomeTotemResource(RespuestaEventoHomeTotem::with(['evento'])->get());
    }

    public function store(StoreRespuestaEventoHomeTotemRequest $request)
    {
        $respuestaEventoHomeTotem = RespuestaEventoHomeTotem::create($request->all());

        return (new RespuestaEventoHomeTotemResource($respuestaEventoHomeTotem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespuestaEventoHomeTotemResource($respuestaEventoHomeTotem->load(['evento']));
    }

    public function update(UpdateRespuestaEventoHomeTotemRequest $request, RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        $respuestaEventoHomeTotem->update($request->all());

        return (new RespuestaEventoHomeTotemResource($respuestaEventoHomeTotem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
