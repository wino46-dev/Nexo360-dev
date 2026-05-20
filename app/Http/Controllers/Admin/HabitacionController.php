<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHabitacionRequest;
use App\Http\Requests\StoreHabitacionRequest;
use App\Http\Requests\UpdateHabitacionRequest;
use App\Models\Establecimiento;
use App\Models\Habitacion;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class HabitacionController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('habitacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Habitacion::with(['establecimiento'])->select(sprintf('%s.*', (new Habitacion)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'habitacion_show';
                $editGate      = 'habitacion_edit';
                $deleteGate    = 'habitacion_delete';
                $crudRoutePart = 'habitacions';

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
            $table->addColumn('establecimiento_codigo', function ($row) {
                return $row->establecimiento ? $row->establecimiento->codigo : '';
            });

            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();
        $teams            = Team::get();

        return view('admin.habitacions.index', compact('establecimientos', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('habitacion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.habitacions.create', compact('establecimientos'));
    }

    public function store(StoreHabitacionRequest $request)
    {
        $habitacion = Habitacion::create($request->all());

        return redirect()->route('admin.habitacions.index');
    }

    public function edit(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacion->load('establecimiento');

        return view('admin.habitacions.edit', compact('establecimientos', 'habitacion'));
    }

    public function update(UpdateHabitacionRequest $request, Habitacion $habitacion)
    {
        $habitacion->update($request->all());

        return redirect()->route('admin.habitacions.index');
    }

    public function show(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $habitacion->load('establecimiento');

        return view('admin.habitacions.show', compact('habitacion'));
    }

    public function destroy(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $habitacion->delete();

        return back();
    }

    public function massDestroy(MassDestroyHabitacionRequest $request)
    {
        $habitacions = Habitacion::find(request('ids'));

        foreach ($habitacions as $habitacion) {
            $habitacion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
