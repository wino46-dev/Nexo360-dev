<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocMetodoPagoHotelRequest;
use App\Http\Requests\UpdateDocMetodoPagoHotelRequest;
use App\Http\Resources\Admin\DocMetodoPagoHotelResource;
use App\Models\DocMetodoPagoHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocMetodoPagoHotelApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocMetodoPagoHotelResource(DocMetodoPagoHotel::with(['establecimiento'])->get());
    }

    public function store(StoreDocMetodoPagoHotelRequest $request)
    {
        $docMetodoPagoHotel = DocMetodoPagoHotel::create($request->all());

        return (new DocMetodoPagoHotelResource($docMetodoPagoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocMetodoPagoHotelResource($docMetodoPagoHotel->load(['establecimiento']));
    }

    public function update(UpdateDocMetodoPagoHotelRequest $request, DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        $docMetodoPagoHotel->update($request->all());

        return (new DocMetodoPagoHotelResource($docMetodoPagoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docMetodoPagoHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
