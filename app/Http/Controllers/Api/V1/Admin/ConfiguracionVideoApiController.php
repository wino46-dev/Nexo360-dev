<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionVideoRequest;
use App\Http\Requests\UpdateConfiguracionVideoRequest;
use App\Http\Resources\Admin\ConfiguracionVideoResource;
use App\Models\ConfiguracionVideo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionVideoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_video_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionVideoResource(ConfiguracionVideo::with(['totem'])->get());
    }

    public function store(StoreConfiguracionVideoRequest $request)
    {
        $configuracionVideo = ConfiguracionVideo::create($request->all());

        return (new ConfiguracionVideoResource($configuracionVideo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ConfiguracionVideo $configuracionVideo)
    {
        abort_if(Gate::denies('configuracion_video_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfiguracionVideoResource($configuracionVideo->load(['totem']));
    }

    public function update(UpdateConfiguracionVideoRequest $request, ConfiguracionVideo $configuracionVideo)
    {
        $configuracionVideo->update($request->all());

        return (new ConfiguracionVideoResource($configuracionVideo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
