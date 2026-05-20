<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTipoEventoRequest;
use App\Http\Requests\StoreTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Models\Team;
use App\Models\TipoEvento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TipoEventoController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tipo_evento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = TipoEvento::select(sprintf('%s.*', (new TipoEvento)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'tipo_evento_show';
                $editGate      = 'tipo_evento_edit';
                $deleteGate    = 'tipo_evento_delete';
                $crudRoutePart = 'tipo-eventos';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('external.tipoEventos.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('tipo_evento_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.tipoEventos.create');
    }

    public function store(StoreTipoEventoRequest $request)
    {
        $tipoEvento = TipoEvento::create($request->all());

        return redirect()->route('external.tipo-eventos.index');
    }

    public function edit(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('external.tipoEventos.edit', compact('tipoEvento'));
    }

    public function update(UpdateTipoEventoRequest $request, TipoEvento $tipoEvento)
    {
        $tipoEvento->update($request->all());

        return redirect()->route('external.tipo-eventos.index');
    }

    public function show(TipoEvento $tipoEvento)
    {
        abort_if(Gate::denies('tipo_evento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('external.tipoEventos.show', compact('tipoEvento'));
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
