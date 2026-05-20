<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocTarifaHotelRequest;
use App\Http\Requests\UpdateDocTarifaHotelRequest;
use App\Http\Resources\Admin\DocTarifaHotelResource;
use App\Models\DocTarifaHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocTarifaHotelApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doc_tarifa_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocTarifaHotelResource(DocTarifaHotel::with(['establecimiento', 'habitacion'])->get());
    }

    public function store(StoreDocTarifaHotelRequest $request)
    {
        $docTarifaHotel = DocTarifaHotel::create($request->all());

        return (new DocTarifaHotelResource($docTarifaHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocTarifaHotelResource($docTarifaHotel->load(['establecimiento', 'habitacion']));
    }

    public function update(UpdateDocTarifaHotelRequest $request, DocTarifaHotel $docTarifaHotel)
    {
        $docTarifaHotel->update($request->all());

        return (new DocTarifaHotelResource($docTarifaHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docTarifaHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
