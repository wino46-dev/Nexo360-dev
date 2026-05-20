<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionGrabadorController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('configuracion_grabador_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ConfiguracionGrabador::with(['totem'])->select(sprintf('%s.*', (new ConfiguracionGrabador)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'configuracion_grabador_show';
                $editGate      = 'configuracion_grabador_edit';
                $deleteGate    = 'configuracion_grabador_delete';
                $crudRoutePart = 'configuracion-grabadors';

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

            $table->editColumn('software_gestion', function ($row) {
                return $row->software_gestion ? ConfiguracionGrabador::SOFTWARE_GESTION_SELECT[$row->software_gestion] : '';
            });
            $table->editColumn('reader_no', function ($row) {
                return $row->reader_no ? $row->reader_no : '';
            });
            $table->editColumn('seq_mode', function ($row) {
                return $row->seq_mode ? $row->seq_mode : '';
            });
            $table->editColumn('show_message', function ($row) {
                return $row->show_message ? $row->show_message : '';
            });
            $table->editColumn('user_host', function ($row) {
                return $row->user_host ? $row->user_host : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'totem']);

            return $table->make(true);
        }

        $totems = Totem::get();
        $teams  = Team::get();

        return view('admin.configuracionGrabadors.index', compact('totems', 'teams'));
    }

    public function create()
    {
        return redirect()->back();

        abort_if(Gate::denies('configuracion_grabador_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.configuracionGrabadors.create', compact('totems'));
    }

    public function store(StoreConfiguracionGrabadorRequest $request)
    {
        $configuracionGrabador = ConfiguracionGrabador::create($request->all());

        return redirect()->back();

        return redirect()->route('admin.configuracion-grabadors.index');
    }

    public function edit(ConfiguracionGrabador $configuracionGrabador)
    {
        return redirect()->back();

        abort_if(Gate::denies('configuracion_grabador_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $configuracionGrabador->load('totem');

        return view('admin.configuracionGrabadors.edit', compact('configuracionGrabador', 'totems'));
    }

    public function update(UpdateConfiguracionGrabadorRequest $request, ConfiguracionGrabador $configuracionGrabador)
    {
        $configuracionGrabador->update($request->all());

        return redirect()->back();

        return redirect()->route('admin.configuracion-grabadors.index');
    }

    public function show(ConfiguracionGrabador $configuracionGrabador)
    {
        abort_if(Gate::denies('configuracion_grabador_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuracionGrabador->load('totem');

        return view('admin.configuracionGrabadors.show', compact('configuracionGrabador'));
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
