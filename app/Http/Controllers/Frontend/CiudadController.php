<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCiudadRequest;
use App\Http\Requests\StoreCiudadRequest;
use App\Http\Requests\UpdateCiudadRequest;
use App\Models\Ciudad;
use App\Models\Provincium;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CiudadController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ciudad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ciudads = Ciudad::with(['provincia'])->get();

        $provincia = Provincium::get();



        return view('frontend.ciudads.index', compact('ciudads', 'provincia'));
    }

    public function create()
    {
        abort_if(Gate::denies('ciudad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.ciudads.create', compact('provincias'));
    }

    public function store(StoreCiudadRequest $request)
    {
        $ciudad = Ciudad::create($request->all());

        return redirect()->route('frontend.ciudads.index');
    }

    public function edit(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudad->load('provincia');

        return view('frontend.ciudads.edit', compact('ciudad', 'provincias'));
    }

    public function update(UpdateCiudadRequest $request, Ciudad $ciudad)
    {
        $ciudad->update($request->all());

        return redirect()->route('frontend.ciudads.index');
    }

    public function show(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ciudad->load('provincia');

        return view('frontend.ciudads.show', compact('ciudad'));
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
