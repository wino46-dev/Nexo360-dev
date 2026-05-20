<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClienteRequest;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\Pai;
use App\Models\Provincium;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('cliente_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Cliente::with(['pais', 'provincia', 'ciudad'])->select(sprintf('%s.*', (new Cliente)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'cliente_show';
                $editGate      = 'cliente_edit';
                $deleteGate    = 'cliente_delete';
                $crudRoutePart = 'clientes';

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
            $table->editColumn('apellidos', function ($row) {
                return $row->apellidos ? $row->apellidos : '';
            });
            $table->editColumn('nif', function ($row) {
                return $row->nif ? $row->nif : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        $pais      = Pai::get();
        $provincia = Provincium::get();
        $ciudads   = Ciudad::get();
        $teams     = Team::get();

        return view('external.clientes.index', compact('pais', 'provincia', 'ciudads', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('cliente_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.clientes.create', compact('ciudads', 'pais', 'provincias'));
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = Cliente::create($request->all());

        return redirect()->route('external.clientes.index');
    }

    public function edit(Cliente $cliente)
    {
        abort_if(Gate::denies('cliente_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cliente->load('pais', 'provincia', 'ciudad');

        return view('external.clientes.edit', compact('ciudads', 'cliente', 'pais', 'provincias'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->all());

        return redirect()->route('external.clientes.index');
    }

    public function show(Cliente $cliente)
    {
        abort_if(Gate::denies('cliente_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cliente->load('pais', 'provincia', 'ciudad', 'clienteReservas', 'clienteCheckIns');

        return view('external.clientes.show', compact('cliente'));
    }

    public function destroy(Cliente $cliente)
    {
        abort_if(Gate::denies('cliente_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cliente->delete();

        return back();
    }

    public function massDestroy(MassDestroyClienteRequest $request)
    {
        $clientes = Cliente::find(request('ids'));

        foreach ($clientes as $cliente) {
            $cliente->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
