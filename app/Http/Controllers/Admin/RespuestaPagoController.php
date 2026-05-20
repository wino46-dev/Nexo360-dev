<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRespuestaPagoRequest;
use App\Http\Requests\StoreRespuestaPagoRequest;
use App\Http\Requests\UpdateRespuestaPagoRequest;
use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RespuestaPagoController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('respuesta_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RespuestaPago::with(['pago_origen'])->select(sprintf('%s.*', (new RespuestaPago)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'respuesta_pago_show';
                $editGate      = 'respuesta_pago_edit';
                $deleteGate    = 'respuesta_pago_delete';
                $crudRoutePart = 'respuesta-pagos';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('pago_origen_factura', function ($row) {
                return $row->pago_origen ? $row->pago_origen->factura : '';
            });

            $table->editColumn('tipo_pago', function ($row) {
                return $row->tipo_pago ? $row->tipo_pago : '';
            });
            $table->editColumn('tipo_oper', function ($row) {
                return $row->tipo_oper ? $row->tipo_oper : '';
            });
            $table->editColumn('importe', function ($row) {
                return $row->importe ? $row->importe : '';
            });
            $table->editColumn('moneda', function ($row) {
                return $row->moneda ? $row->moneda : '';
            });
            $table->editColumn('tarjeta', function ($row) {
                return $row->tarjeta ? $row->tarjeta : '';
            });
            $table->editColumn('identificador_rts_base', function ($row) {
                return $row->identificador_rts_base ? $row->identificador_rts_base : '';
            });
            $table->editColumn('fecha_operacion', function ($row) {
                return $row->fecha_operacion ? $row->fecha_operacion : '';
            });
            $table->editColumn('resultado', function ($row) {
                return $row->resultado ? $row->resultado : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'pago_origen']);

            return $table->make(true);
        }

        $pago_totems = PagoTotem::get();
        $teams       = Team::get();

        return view('admin.respuestaPagos.index', compact('pago_totems', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('respuesta_pago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pago_origens = PagoTotem::pluck('factura', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.respuestaPagos.create', compact('pago_origens'));
    }

    public function store(StoreRespuestaPagoRequest $request)
    {
        $respuestaPago = RespuestaPago::create($request->all());

        return redirect()->route('admin.respuesta-pagos.index');
    }

    public function edit(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pago_origens = PagoTotem::pluck('factura', 'id')->prepend(trans('global.pleaseSelect'), '');

        $respuestaPago->load('pago_origen');

        return view('admin.respuestaPagos.edit', compact('pago_origens', 'respuestaPago'));
    }

    public function update(UpdateRespuestaPagoRequest $request, RespuestaPago $respuestaPago)
    {
        $respuestaPago->update($request->all());

        return redirect()->route('admin.respuesta-pagos.index');
    }

    public function show(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaPago->load('pago_origen');

        return view('admin.respuestaPagos.show', compact('respuestaPago'));
    }

    public function destroy(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaPago->delete();

        return back();
    }

    public function massDestroy(MassDestroyRespuestaPagoRequest $request)
    {
        $respuestaPagos = RespuestaPago::find(request('ids'));

        foreach ($respuestaPagos as $respuestaPago) {
            $respuestaPago->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
