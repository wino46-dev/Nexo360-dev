<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRespuestaEventoHomeTotemRequest;
use App\Http\Requests\StoreRespuestaEventoHomeTotemRequest;
use App\Http\Requests\UpdateRespuestaEventoHomeTotemRequest;
use App\Models\EventoHomeTotem;
use App\Models\RespuestaEventoHomeTotem;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RespuestaEventoHomeTotemController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RespuestaEventoHomeTotem::with(['evento'])->select(sprintf('%s.*', (new RespuestaEventoHomeTotem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'respuesta_evento_home_totem_show';
                $editGate      = 'respuesta_evento_home_totem_edit';
                $deleteGate    = 'respuesta_evento_home_totem_delete';
                $crudRoutePart = 'respuesta-evento-home-totems';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('evento_objeto', function ($row) {
                return $row->evento ? $row->evento->objeto : '';
            });

            $table->editColumn('respuesta', function ($row) {
                return $row->respuesta ? $row->respuesta : '';
            });
            $table->editColumn('estado', function ($row) {
                return $row->estado ? RespuestaEventoHomeTotem::ESTADO_SELECT[$row->estado] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'evento']);

            return $table->make(true);
        }

        $evento_home_totems = EventoHomeTotem::get();
        $teams              = Team::get();

        return view('admin.respuestaEventoHomeTotems.index', compact('evento_home_totems', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventos = EventoHomeTotem::pluck('objeto', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.respuestaEventoHomeTotems.create', compact('eventos'));
    }

    public function store(StoreRespuestaEventoHomeTotemRequest $request)
    {
        $respuestaEventoHomeTotem = RespuestaEventoHomeTotem::create($request->all());

        return redirect()->route('admin.respuesta-evento-home-totems.index');
    }

    public function edit(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventos = EventoHomeTotem::pluck('objeto', 'id')->prepend(trans('global.pleaseSelect'), '');

        $respuestaEventoHomeTotem->load('evento');

        return view('admin.respuestaEventoHomeTotems.edit', compact('eventos', 'respuestaEventoHomeTotem'));
    }

    public function update(UpdateRespuestaEventoHomeTotemRequest $request, RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        $respuestaEventoHomeTotem->update($request->all());

        return redirect()->route('admin.respuesta-evento-home-totems.index');
    }

    public function show(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotem->load('evento');

        return view('admin.respuestaEventoHomeTotems.show', compact('respuestaEventoHomeTotem'));
    }

    public function destroy(RespuestaEventoHomeTotem $respuestaEventoHomeTotem)
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaEventoHomeTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyRespuestaEventoHomeTotemRequest $request)
    {
        $respuestaEventoHomeTotems = RespuestaEventoHomeTotem::find(request('ids'));

        foreach ($respuestaEventoHomeTotems as $respuestaEventoHomeTotem) {
            $respuestaEventoHomeTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
