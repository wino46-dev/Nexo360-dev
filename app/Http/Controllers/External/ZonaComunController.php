<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyZonaComunRequest;
use App\Http\Requests\StoreZonaComunRequest;
use App\Http\Requests\UpdateZonaComunRequest;
use App\Models\Establecimiento;
use App\Models\ZonaComun;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ZonaComunController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('zona_comun_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ZonaComun::with(['establecimiento'])->select(sprintf('%s.*', (new ZonaComun)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'zona_comun_show';
                $editGate      = 'zona_comun_edit';
                $deleteGate    = 'zona_comun_delete';
                $crudRoutePart = 'zona-comuns';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '0';
            });
            $table->addColumn('establecimiento_nombre', function ($row) {
                return $row->establecimiento ? $row->establecimiento->nombre : '';
            });
            $table->editColumn('orden', function ($row) {
                return $row->orden ? $row->orden : '0';
            });

            $table->editColumn('estado', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->estado ? 'checked' : null) . '>';
            });
            $table->editColumn('global', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->global ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'estado', 'global']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::orderBy('nombre', 'asc')->get();

        return view('external.zonaComuns.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('zona_comun_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::orderBy('nombre', 'asc')->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.zonaComuns.create', compact('establecimientos'));
    }

    public function store(StoreZonaComunRequest $request)
    {
        $data = $request->all();

        if(!empty($data['orden'])){
            $zonaComunFind = ZonaComun::where('establecimiento_id', $data['establecimiento_id'])
                ->where('orden', $data['orden'])
                ->first();

            if($zonaComunFind){
                return redirect()->back()->withErrors(['orden' => 'Este numero de orden esta repetido ('.$zonaComunFind->nombre.')']);
            }
        }

        $zonaComun = ZonaComun::create($request->all());

        return redirect()->route('external.zona-comuns.index');
    }

    public function edit(ZonaComun $zonaComun)
    {
        abort_if(Gate::denies('zona_comun_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::orderBy('nombre', 'asc')->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $zonaComun->load('establecimiento');

        return view('external.zonaComuns.edit', compact('establecimientos', 'zonaComun'));
    }

    public function update(UpdateZonaComunRequest $request, ZonaComun $zonaComun)
    {
        $data = $request->all();

        $zona_comun_id = $zonaComun->id;

        if(!empty($data['orden'])){
            $zonaComunFind = ZonaComun::where('establecimiento_id', $data['establecimiento_id'])
                ->where('id', '<>', $zona_comun_id)
                ->where('orden', $data['orden'])
                ->first();

            if($zonaComunFind){
                return redirect()->back()->withErrors(['orden' => 'Este numero de orden esta repetido ('.$zonaComunFind->nombre.')']);
            }
        }

        $zonaComun->update($request->all());

        return redirect()->route('external.zona-comuns.index');
    }

    public function show(ZonaComun $zonaComun)
    {
        abort_if(Gate::denies('zona_comun_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zonaComun->load('establecimiento');

        return view('external.zonaComuns.show', compact('zonaComun'));
    }

    public function destroy(ZonaComun $zonaComun)
    {
        abort_if(Gate::denies('zona_comun_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zonaComun->delete();

        return back();
    }

    public function massDestroy(MassDestroyZonaComunRequest $request)
    {
        $zonaComuns = ZonaComun::find(request('ids'));

        foreach ($zonaComuns as $zonaComun) {
            $zonaComun->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
