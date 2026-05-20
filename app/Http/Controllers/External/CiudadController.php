<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCiudadRequest;
use App\Http\Requests\StoreCiudadRequest;
use App\Http\Requests\UpdateCiudadRequest;
use App\Models\Ciudad;
use App\Models\Provincium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CiudadController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ciudad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ciudad::with(['provincia'])->select(sprintf('%s.*', (new Ciudad)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ciudad_show';
                $editGate      = 'ciudad_edit';
                $deleteGate    = 'ciudad_delete';
                $crudRoutePart = 'ciudads';

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
            $table->addColumn('provincia_nombre', function ($row) {
                return $row->provincia ? $row->provincia->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'provincia']);

            return $table->make(true);
        }

        $provincia = Provincium::get();

        return view('external.ciudads.index', compact('provincia'));
    }

    public function create()
    {
        abort_if(Gate::denies('ciudad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.ciudads.create', compact('provincias'));
    }

    public function store(StoreCiudadRequest $request)
    {
        $ciudad = Ciudad::create($request->all());

        return redirect()->route('external.ciudads.index');
    }

    public function edit(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad->load('provincia');

        return view('external.ciudads.edit', compact('ciudad', 'provincias'));
    }

    public function update(UpdateCiudadRequest $request, Ciudad $ciudad)
    {
        $ciudad->update($request->all());

        return redirect()->route('external.ciudads.index');
    }

    public function show(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ciudad->load('provincia', 'ciudadDocInfoHotels');

        return view('external.ciudads.show', compact('ciudad'));
    }

    public function destroy(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ciudad->delete();

        return back();
    }

    public function massDestroy(MassDestroyCiudadRequest $request)
    {
        $ciudads = Ciudad::find(request('ids'));

        foreach ($ciudads as $ciudad) {
            $ciudad->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
