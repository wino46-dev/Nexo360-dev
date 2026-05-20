<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocInfoHotelRequest;
use App\Http\Requests\UpdateDocInfoHotelRequest;
use App\Http\Resources\Admin\DocInfoHotelResource;
use App\Models\DocInfoHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocInfoHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_info_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocInfoHotelResource(DocInfoHotel::with(['hotel', 'pais', 'provincia', 'ciudad'])->get());
    }

    public function store(StoreDocInfoHotelRequest $request)
    {
        $docInfoHotel = DocInfoHotel::create($request->all());

        return (new DocInfoHotelResource($docInfoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocInfoHotelResource($docInfoHotel->load(['hotel', 'pais', 'provincia', 'ciudad']));
    }

    public function update(UpdateDocInfoHotelRequest $request, DocInfoHotel $docInfoHotel)
    {
        $docInfoHotel->update($request->all());

        return (new DocInfoHotelResource($docInfoHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docInfoHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
