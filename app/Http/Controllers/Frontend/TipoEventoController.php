<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTipoEventoRequest;
use App\Http\Requests\StoreTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Models\Team;
use App\Models\TipoEvento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoEventoController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tipo_evento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoEventos = TipoEvento::get();



        return view('frontend.tipoEventos.index', compact( 'tipoEventos'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_evento_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.tipoEventos.create');
    }

    public function store(StoreTipoEventoRequest $request)
    {
        $tipoEvento = TipoEvento::create($request->all());

        return redirect()->route('frontend.tipo-eventos.index');
    }

    public function edit(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('frontend.tipoEventos.edit', compact('tipoEvento'));
    }

    public function update(UpdateTipoEventoRequest $request, TipoEvento $tipoEvento)
    {
        $tipoEvento->update($request->all());

        return redirect()->route('frontend.tipo-eventos.index');
    }

    public function show(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('frontend.tipoEventos.show', compact('tipoEvento'));
    }

    public function destroy(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipoEvento->delete();

        return back();
    }

    public function massDestroy(MassDestroyTipoEventoRequest $request)
    {
        $tipoEventos = TipoEvento::find(request('ids'));

        foreach ($tipoEventos as $tipoEvento) {
            $tipoEvento->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
