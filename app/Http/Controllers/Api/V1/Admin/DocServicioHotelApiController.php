<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocServicioHotelRequest;
use App\Http\Requests\UpdateDocServicioHotelRequest;
use App\Http\Resources\Admin\DocServicioHotelResource;
use App\Models\DocServicioHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocServicioHotelApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doc_servicio_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocServicioHotelResource(DocServicioHotel::with(['hotel'])->get());
    }

    public function store(StoreDocServicioHotelRequest $request)
    {
        $docServicioHotel = DocServicioHotel::create($request->all());

        return (new DocServicioHotelResource($docServicioHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocServicioHotelResource($docServicioHotel->load(['hotel']));
    }

    public function update(UpdateDocServicioHotelRequest $request, DocServicioHotel $docServicioHotel)
    {
        $docServicioHotel->update($request->all());

        return (new DocServicioHotelResource($docServicioHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docServicioHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
