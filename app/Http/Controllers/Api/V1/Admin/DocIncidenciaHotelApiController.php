<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocIncidenciaHotelRequest;
use App\Http\Requests\UpdateDocIncidenciaHotelRequest;
use App\Http\Resources\Admin\DocIncidenciaHotelResource;
use App\Models\DocIncidenciaHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocIncidenciaHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_incidencia_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocIncidenciaHotelResource(DocIncidenciaHotel::with(['establecimiento'])->get());
    }

    public function abiertasPorHotel($establecimientoId, Request $request)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = DocIncidenciaHotel::with(['establecimiento'])
            ->where('establecimiento_id', $establecimientoId)
            ->where('estado', 'Abierta');

        return new DocIncidenciaHotelResource($query->get());
    }

    public function store(StoreDocIncidenciaHotelRequest $request)
    {
        $docIncidenciaHotel = DocIncidenciaHotel::create($request->all());

        return (new DocIncidenciaHotelResource($docIncidenciaHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocIncidenciaHotelResource($docIncidenciaHotel->load(['establecimiento']));
    }

    public function update(UpdateDocIncidenciaHotelRequest $request, DocIncidenciaHotel $docIncidenciaHotel)
    {
        $docIncidenciaHotel->update($request->all());

        return (new DocIncidenciaHotelResource($docIncidenciaHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docIncidenciaHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
