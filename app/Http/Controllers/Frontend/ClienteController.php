<?php

namespace App\Http\Controllers\Frontend;

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

class ClienteController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cliente_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clientes = Cliente::with(['pais', 'provincia', 'ciudad'])->get();

        $pais = Pai::get();

        $provincia = Provincium::get();

        $ciudads = Ciudad::get();



        return view('frontend.clientes.index', compact('ciudads', 'clientes', 'pais', 'provincia'));
    }

    public function create()
    {
        abort_if(Gate::denies('cliente_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.clientes.create', compact('ciudads', 'pais', 'provincias'));
    }

    public function store(StoreClienteRequest $request)
    {
        $cliente = Cliente::create($request->all());

        return redirect()->route('frontend.clientes.index');
    }

    public function edit(Cliente $cliente)
    {
        abort_if(Gate::denies('cliente_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cliente->load('pais', 'provincia', 'ciudad');

        return view('frontend.clientes.edit', compact('ciudads', 'cliente', 'pais', 'provincias'));
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->all());

        return redirect()->route('frontend.clientes.index');
    }

    public function show(Cliente $cliente)
    {
        abort_if(Gate::denies('cliente_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cliente->load('pais', 'provincia', 'ciudad', 'clienteReservas');

        return view('frontend.clientes.show', compact('cliente'));
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
