<?php

namespace App\Http\Controllers\Frontend;

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

class HabitacionController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('habitacion_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $habitacions = Habitacion::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();



        return view('frontend.habitacions.index', compact('establecimientos', 'habitacions'));
    }

    public function create()
    {
        abort_if(Gate::denies('habitacion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.habitacions.create', compact('establecimientos'));
    }

    public function store(StoreHabitacionRequest $request)
    {
        $habitacion = Habitacion::create($request->all());

        return redirect()->route('frontend.habitacions.index');
    }

    public function edit(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacion->load('establecimiento');

        return view('frontend.habitacions.edit', compact('establecimientos', 'habitacion'));
    }

    public function update(UpdateHabitacionRequest $request, Habitacion $habitacion)
    {
        $habitacion->update($request->all());

        return redirect()->route('frontend.habitacions.index');
    }

    public function show(Habitacion $habitacion)
    {
        abort_if(Gate::denies('habitacion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $habitacion->load('establecimiento');

        return view('frontend.habitacions.show', compact('habitacion'));
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
