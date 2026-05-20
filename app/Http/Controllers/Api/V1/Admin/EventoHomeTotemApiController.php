<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreEventoHomeTotemRequest;
use App\Http\Requests\UpdateEventoHomeTotemRequest;
use App\Http\Resources\Admin\EventoHomeTotemResource;
use App\Models\EventoHomeTotem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventoHomeTotemApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventoHomeTotemResource(EventoHomeTotem::with(['emisor', 'receptor', 'tipo_evento'])->get());
    }

    public function store(StoreEventoHomeTotemRequest $request)
    {
        $eventoHomeTotem = EventoHomeTotem::create($request->all());

        if ($request->input('respuesta_imagen', false)) {
            $eventoHomeTotem->addMedia(storage_path('tmp/uploads/' . basename($request->input('respuesta_imagen'))))->toMediaCollection('respuesta_imagen');
        }

        return (new EventoHomeTotemResource($eventoHomeTotem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EventoHomeTotemResource($eventoHomeTotem->load(['emisor', 'receptor', 'tipo_evento']));
    }

    public function update(UpdateEventoHomeTotemRequest $request, EventoHomeTotem $eventoHomeTotem)
    {
        $eventoHomeTotem->update($request->all());

        if ($request->input('respuesta_imagen', false)) {
            if (! $eventoHomeTotem->respuesta_imagen || $request->input('respuesta_imagen') !== $eventoHomeTotem->respuesta_imagen->file_name) {
                if ($eventoHomeTotem->respuesta_imagen) {
                    $eventoHomeTotem->respuesta_imagen->delete();
                }
                $eventoHomeTotem->addMedia(storage_path('tmp/uploads/' . basename($request->input('respuesta_imagen'))))->toMediaCollection('respuesta_imagen');
            }
        } elseif ($eventoHomeTotem->respuesta_imagen) {
            $eventoHomeTotem->respuesta_imagen->delete();
        }

        return (new EventoHomeTotemResource($eventoHomeTotem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
