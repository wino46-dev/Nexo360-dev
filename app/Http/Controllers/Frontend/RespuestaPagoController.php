<?php

namespace App\Http\Controllers\Frontend;

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

class RespuestaPagoController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('respuesta_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaPagos = RespuestaPago::with(['pago_origen'])->get();

        $pago_totems = PagoTotem::get();



        return view('frontend.respuestaPagos.index', compact('pago_totems', 'respuestaPagos'));
    }

    public function create()
    {
        abort_if(Gate::denies('respuesta_pago_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pago_origens = PagoTotem::pluck('factura', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.respuestaPagos.create', compact('pago_origens'));
    }

    public function store(StoreRespuestaPagoRequest $request)
    {
        $respuestaPago = RespuestaPago::create($request->all());

        return redirect()->route('frontend.respuesta-pagos.index');
    }

    public function edit(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pago_origens = PagoTotem::pluck('factura', 'id')->prepend(trans('global.pleaseSelect'), '');

        $respuestaPago->load('pago_origen');

        return view('frontend.respuestaPagos.edit', compact('pago_origens', 'respuestaPago'));
    }

    public function update(UpdateRespuestaPagoRequest $request, RespuestaPago $respuestaPago)
    {
        $respuestaPago->update($request->all());

        return redirect()->route('frontend.respuesta-pagos.index');
    }

    public function show(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaPago->load('pago_origen');

        return view('frontend.respuestaPagos.show', compact('respuestaPago'));
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
