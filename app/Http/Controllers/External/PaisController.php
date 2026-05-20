<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyPaiRequest;
use App\Http\Requests\StorePaiRequest;
use App\Http\Requests\UpdatePaiRequest;
use App\Models\Pai;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaisController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('pai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Pai::query()->select(sprintf('%s.*', (new Pai)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'pai_show';
                $editGate      = 'pai_edit';
                $deleteGate    = 'pai_delete';
                $crudRoutePart = 'pais';

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
                return $row->codigo ? $row->codigo : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('external.pais.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pai_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.pais.create');
    }

    public function store(StorePaiRequest $request)
    {
        $pai = Pai::create($request->all());

        return redirect()->route('external.pais.index');
    }

    public function edit(Pai $pai)
    {
        abort_if(Gate::denies('pai_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.pais.edit', compact('pai'));
    }

    public function update(UpdatePaiRequest $request, Pai $pai)
    {
        $pai->update($request->all());

        return redirect()->route('external.pais.index');
    }

    public function show(Pai $pai)
    {
        abort_if(Gate::denies('pai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pai->load('paisProvincia', 'paisDocInfoHotels');

        return view('external.pais.show', compact('pai'));
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
