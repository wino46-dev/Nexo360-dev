<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyParteViajeroRequest;
use App\Http\Requests\StoreParteViajeroRequest;
use App\Http\Requests\UpdateParteViajeroRequest;
use App\Models\Cliente;
use App\Models\ParteViajero;
use App\Models\Reserva;
use App\Models\Reservation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ParteViajeroController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('parte_viajero_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ParteViajero::with(['reserva', 'cliente'])->select(sprintf('%s.*', (new ParteViajero)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'parte_viajero_show';
                $editGate      = 'parte_viajero_edit';
                $deleteGate    = 'parte_viajero_delete';
                $crudRoutePart = 'parte-viajeros';

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
            $table->addColumn('reserva_codigo', function ($row) {
                return $row->reserva ? $row->reserva->codigo : '';
            });

            $table->addColumn('cliente_nombre', function ($row) {
                return $row->cliente ? $row->cliente->nombre : '';
            });

            $table->editColumn('texto_inferior', function ($row) {
                return $row->texto_inferior ? $row->texto_inferior : '';
            });
            $table->editColumn('firma', function ($row) {
                return $row->firma ? $row->firma : '';
            });
            $table->editColumn('envio_mail', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->envio_mail ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'reserva', 'cliente', 'envio_mail']);

            return $table->make(true);
        }

        $reservas = Reservation::get();
        $clientes = Cliente::get();

        return view('external.parteViajeros.index', compact('reservas', 'clientes'));
    }

    public function create()
    {
        abort_if(Gate::denies('parte_viajero_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.parteViajeros.create', compact('clientes', 'reservas'));
    }

    public function store(StoreParteViajeroRequest $request)
    {
        $parteViajero = ParteViajero::create($request->all());

        return redirect()->route('external.parte-viajeros.index');
    }

    public function edit(ParteViajero $parteViajero)
    {
        abort_if(Gate::denies('parte_viajero_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reservas = Reservation::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clientes = Cliente::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parteViajero->load('reserva', 'cliente');

        return view('external.parteViajeros.edit', compact('clientes', 'parteViajero', 'reservas'));
    }

    public function update(UpdateParteViajeroRequest $request, ParteViajero $parteViajero)
    {
        $parteViajero->update($request->all());

        return redirect()->route('external.parte-viajeros.index');
    }

    public function show(ParteViajero $parteViajero)
    {
        abort_if(Gate::denies('parte_viajero_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parteViajero->load('reserva', 'cliente');

        return view('external.parteViajeros.show', compact('parteViajero'));
    }

    public function destroy(ParteViajero $parteViajero)
    {
        abort_if(Gate::denies('parte_viajero_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parteViajero->delete();

        return back();
    }

    public function massDestroy(MassDestroyParteViajeroRequest $request)
    {
        $parteViajeros = ParteViajero::find(request('ids'));

        foreach ($parteViajeros as $parteViajero) {
            $parteViajero->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
