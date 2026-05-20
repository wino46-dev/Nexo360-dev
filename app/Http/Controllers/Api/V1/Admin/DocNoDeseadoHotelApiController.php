<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocNoDeseadoHotelRequest;
use App\Http\Requests\UpdateDocNoDeseadoHotelRequest;
use App\Http\Resources\Admin\DocNoDeseadoHotelResource;
use App\Models\DocNoDeseadoHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocNoDeseadoHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocNoDeseadoHotelResource(DocNoDeseadoHotel::with(['establecimiento'])->get());
    }

    public function porHotel($establecimientoId, Request $request)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = DocNoDeseadoHotel::with(['establecimiento'])
            ->where('establecimiento_id', $establecimientoId);

        return new DocNoDeseadoHotelResource($query->get());
    }

    public function store(StoreDocNoDeseadoHotelRequest $request)
    {
        $docNoDeseadoHotel = DocNoDeseadoHotel::create($request->all());

        return (new DocNoDeseadoHotelResource($docNoDeseadoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocNoDeseadoHotelResource($docNoDeseadoHotel->load(['establecimiento']));
    }

    public function update(UpdateDocNoDeseadoHotelRequest $request, DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        $docNoDeseadoHotel->update($request->all());

        return (new DocNoDeseadoHotelResource($docNoDeseadoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
