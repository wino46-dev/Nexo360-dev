<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConfiguracionGrabadorRequest;
use App\Http\Requests\StoreConfiguracionGrabadorRequest;
use App\Http\Requests\UpdateConfiguracionGrabadorRequest;
use App\Models\ConfiguracionGrabador;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfiguracionGrabadorController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuracion_grabador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionGrabadors = ConfiguracionGrabador::with(['totem'])->get();

        $totems = Totem::get();



        return view('frontend.configuracionGrabadors.index', compact('configuracionGrabadors', 'totems'));
    }

    public function create()
    {
        abort_if(Gate::denies('configuracion_grabador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.configuracionGrabadors.create', compact('totems'));
    }

    public function store(StoreConfiguracionGrabadorRequest $request)
    {
        $configuracionGrabador = ConfiguracionGrabador::create($request->all());

        return redirect()->route('frontend.configuracion-grabadors.index');
    }

    public function edit(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $configuracionGrabador->load('totem');

        return view('frontend.configuracionGrabadors.edit', compact('configuracionGrabador', 'totems'));
    }

    public function update(UpdateConfiguracionGrabadorRequest $request, ConfiguracionGrabador $configuracionGrabador)
    {
        $configuracionGrabador->update($request->all());

        return redirect()->route('frontend.configuracion-grabadors.index');
    }

    public function show(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionGrabador->load('totem');

        return view('frontend.configuracionGrabadors.show', compact('configuracionGrabador'));
    }

    public function destroy(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionGrabador->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfiguracionGrabadorRequest $request)
    {
        $configuracionGrabadors = ConfiguracionGrabador::find(request('ids'));

        foreach ($configuracionGrabadors as $configuracionGrabador) {
            $configuracionGrabador->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
