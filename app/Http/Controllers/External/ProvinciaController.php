<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProvinciumRequest;
use App\Http\Requests\StoreProvinciumRequest;
use App\Http\Requests\UpdateProvinciumRequest;
use App\Models\Pai;
use App\Models\Provincium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProvinciaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('provincium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Provincium::with(['pais'])->select(sprintf('%s.*', (new Provincium)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'provincium_show';
                $editGate      = 'provincium_edit';
                $deleteGate    = 'provincium_delete';
                $crudRoutePart = 'provincia';

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
            $table->addColumn('pais_nombre', function ($row) {
                return $row->pais ? $row->pais->nombre : '';
            });

            $table->editColumn('iso', function ($row) {
                return $row->iso ? $row->iso : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'pais']);

            return $table->make(true);
        }

        $pais = Pai::get();

        return view('external.provincia.index', compact('pais'));
    }

    public function create()
    {
        abort_if(Gate::denies('provincium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.provincia.create', compact('pais'));
    }

    public function store(StoreProvinciumRequest $request)
    {
        $provincium = Provincium::create($request->all());

        return redirect()->route('external.provincia.index');
    }

    public function edit(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincium->load('pais');

        return view('external.provincia.edit', compact('pais', 'provincium'));
    }

    public function update(UpdateProvinciumRequest $request, Provincium $provincium)
    {
        $provincium->update($request->all());

        return redirect()->route('external.provincia.index');
    }

    public function show(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincium->load('pais', 'provinciaCiudads', 'provinciaDocInfoHotels');

        return view('external.provincia.show', compact('provincium'));
    }

    public function destroy(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincium->delete();

        return back();
    }

    public function massDestroy(MassDestroyProvinciumRequest $request)
    {
        $provincia = Provincium::find(request('ids'));

        foreach ($provincia as $provincium) {
            $provincium->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
