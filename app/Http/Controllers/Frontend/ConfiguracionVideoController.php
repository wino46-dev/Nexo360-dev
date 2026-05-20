<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionVideoRequest;
use App\Http\Requests\UpdateConfiguracionVideoRequest;
use App\Models\ConfiguracionVideo;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionVideoController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_video_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionVideos = ConfiguracionVideo::with(['totem'])->get();

        $totems = Totem::get();



        return view('frontend.configuracionVideos.index', compact('configuracionVideos', 'totems'));
    }

    public function create()
    {
        abort_if(Gate::denies('configuracion_video_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.configuracionVideos.create', compact('totems'));
    }

    public function store(StoreConfiguracionVideoRequest $request)
    {
        $configuracionVideo = ConfiguracionVideo::create($request->all());

        return redirect()->route('frontend.configuracion-videos.index');
    }

    public function edit(ConfiguracionVideo $configuracionVideo)
    {
        abort_if(Gate::denies('configuracion_video_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $configuracionVideo->load('totem');

        return view('frontend.configuracionVideos.edit', compact('configuracionVideo', 'totems'));
    }

    public function update(UpdateConfiguracionVideoRequest $request, ConfiguracionVideo $configuracionVideo)
    {
        $configuracionVideo->update($request->all());

        return redirect()->route('frontend.configuracion-videos.index');
    }

    public function show(ConfiguracionVideo $configuracionVideo)
    {
        abort_if(Gate::denies('configuracion_video_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionVideo->load('totem');

        return view('frontend.configuracionVideos.show', compact('configuracionVideo'));
    }
}
