<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocUbicacionHotelRequest;
use App\Http\Requests\UpdateDocUbicacionHotelRequest;
use App\Http\Resources\Admin\DocUbicacionHotelResource;
use App\Models\DocUbicacionHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocUbicacionHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocUbicacionHotelResource(DocUbicacionHotel::with(['establecimiento'])->get());
    }

    public function store(StoreDocUbicacionHotelRequest $request)
    {
        $docUbicacionHotel = DocUbicacionHotel::create($request->all());

        return (new DocUbicacionHotelResource($docUbicacionHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocUbicacionHotelResource($docUbicacionHotel->load(['establecimiento']));
    }

    public function update(UpdateDocUbicacionHotelRequest $request, DocUbicacionHotel $docUbicacionHotel)
    {
        $docUbicacionHotel->update($request->all());

        return (new DocUbicacionHotelResource($docUbicacionHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docUbicacionHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
