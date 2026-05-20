<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPaiRequest;
use App\Http\Requests\StorePaiRequest;
use App\Http\Requests\UpdatePaiRequest;
use App\Models\Pai;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaisController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('pai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::with(['team'])->get();



        return view('frontend.pais.index', compact('pais'));
    }

    public function create()
    {
        abort_if(Gate::denies('pai_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.pais.create');
    }

    public function store(StorePaiRequest $request)
    {
        $pai = Pai::create($request->all());

        return redirect()->route('frontend.pais.index');
    }

    public function edit(Pai $pai)
    {
        abort_if(Gate::denies('pai_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('frontend.pais.edit', compact('pai'));
    }

    public function update(UpdatePaiRequest $request, Pai $pai)
    {
        $pai->update($request->all());

        return redirect()->route('frontend.pais.index');
    }

    public function show(Pai $pai)
    {
        abort_if(Gate::denies('pai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('frontend.pais.show', compact('pai'));
    }

    public function destroy(Pai $pai)
    {
        abort_if(Gate::denies('pai_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pai->delete();

        return back();
    }

    public function massDestroy(MassDestroyPaiRequest $request)
    {
        $pais = Pai::find(request('ids'));

        foreach ($pais as $pai) {
            $pai->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
