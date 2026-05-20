<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConfiguracionTpvRequest;
use App\Http\Requests\StoreConfiguracionTpvRequest;
use App\Http\Requests\UpdateConfiguracionTpvRequest;
use App\Models\ConfiguracionTpv;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionTpvController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('configuracion_tpv_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ConfiguracionTpv::with(['totem'])->select(sprintf('%s.*', (new ConfiguracionTpv)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'configuracion_tpv_show';
                $editGate      = 'configuracion_tpv_edit';
                $deleteGate    = 'configuracion_tpv_delete';
                $crudRoutePart = 'configuracion-tpvs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('totem_codigo', function ($row) {
                return $row->totem ? $row->totem->codigo : '';
            });

            $table->editColumn('comercio', function ($row) {
                return $row->comercio ? $row->comercio : '';
            });
            $table->editColumn('terminal', function ($row) {
                return $row->terminal ? $row->terminal : '';
            });
            $table->editColumn('conf_puerto', function ($row) {
                return $row->conf_puerto ? $row->conf_puerto : '';
            });
            $table->editColumn('version', function ($row) {
                return $row->version ? $row->version : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'totem']);

            return $table->make(true);
        }

        $totems = Totem::get();
        $teams  = Team::get();

        return view('external.configuracionTpvs.index', compact('totems', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('configuracion_tpv_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return back();

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.configuracionTpvs.create', compact('totems'));
    }

    public function store(StoreConfiguracionTpvRequest $request)
    {
        $configuracionTpv = ConfiguracionTpv::create($request->all());

        return back();
    }

    public function edit(ConfiguracionTpv $configuracionTpv)
    {
        abort_if(Gate::denies('configuracion_tpv_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionTpv->load('totem');

        return back();

        return view('external.configuracionTpvs.edit', compact('configuracionTpv'));
    }

    public function update(UpdateConfiguracionTpvRequest $request, ConfiguracionTpv $configuracionTpv)
    {
        $configuracionTpv->update($request->all());

        return back();
    }

    public function show(ConfiguracionTpv $configuracionTpv)
    {
        abort_if(Gate::denies('configuracion_tpv_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionTpv->load('totem');

        return view('external.configuracionTpvs.show', compact(['configuracionTpv','totems']));
    }

    public function destroy(ConfiguracionTpv $configuracionTpv)
    {
        abort_if(Gate::denies('configuracion_tpv_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionTpv->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfiguracionTpvRequest $request)
    {
        $configuracionTpvs = ConfiguracionTpv::find(request('ids'));

        foreach ($configuracionTpvs as $configuracionTpv) {
            $configuracionTpv->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
