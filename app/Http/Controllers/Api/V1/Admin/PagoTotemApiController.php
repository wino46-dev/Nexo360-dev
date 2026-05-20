<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagoTotemRequest;
use App\Http\Requests\UpdatePagoTotemRequest;
use App\Http\Resources\Admin\PagoTotemResource;
use App\Models\PagoTotem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PagoTotemApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pago_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PagoTotemResource(PagoTotem::with(['emisor', 'receptor', 'sesion'])->get());
    }

    public function store(StorePagoTotemRequest $request)
    {
        $pagoTotem = PagoTotem::create($request->all());

        return (new PagoTotemResource($pagoTotem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PagoTotemResource($pagoTotem->load(['emisor', 'receptor', 'sesion']));
    }

    public function update(UpdatePagoTotemRequest $request, PagoTotem $pagoTotem)
    {
        $pagoTotem->update($request->all());

        return (new PagoTotemResource($pagoTotem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PagoTotem $pagoTotem)
    {
        abort_if(Gate::denies('pago_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pagoTotem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
