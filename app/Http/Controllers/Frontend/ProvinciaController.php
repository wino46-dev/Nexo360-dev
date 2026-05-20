<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProvinciumRequest;
use App\Http\Requests\StoreProvinciumRequest;
use App\Http\Requests\UpdateProvinciumRequest;
use App\Models\Pai;
use App\Models\Provincium;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvinciaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('provincium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincia = Provincium::with(['pais'])->get();

        $pais = Pai::get();



        return view('frontend.provincia.index', compact('pais', 'provincia'));
    }

    public function create()
    {
        abort_if(Gate::denies('provincium_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.provincia.create', compact('pais'));
    }

    public function store(StoreProvinciumRequest $request)
    {
        $provincium = Provincium::create($request->all());

        return redirect()->route('frontend.provincia.index');
    }

    public function edit(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincium->load('pais');

        return view('frontend.provincia.edit', compact('pais', 'provincium'));
    }

    public function update(UpdateProvinciumRequest $request, Provincium $provincium)
    {
        $provincium->update($request->all());

        return redirect()->route('frontend.provincia.index');
    }

    public function show(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincium->load('pais');

        return view('frontend.provincia.show', compact('provincium'));
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
