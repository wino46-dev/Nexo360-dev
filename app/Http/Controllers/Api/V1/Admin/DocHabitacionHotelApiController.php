<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocHabitacionHotelRequest;
use App\Http\Requests\UpdateDocHabitacionHotelRequest;
use App\Http\Resources\Admin\DocHabitacionHotelResource;
use App\Models\DocHabitacionHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocHabitacionHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_habitacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHabitacionHotelResource(DocHabitacionHotel::with(['establecimiento'])->get());
    }

    public function store(StoreDocHabitacionHotelRequest $request)
    {
        $docHabitacionHotel = DocHabitacionHotel::create($request->all());

        return (new DocHabitacionHotelResource($docHabitacionHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHabitacionHotelResource($docHabitacionHotel->load(['establecimiento']));
    }

    public function update(UpdateDocHabitacionHotelRequest $request, DocHabitacionHotel $docHabitacionHotel)
    {
        $docHabitacionHotel->update($request->all());

        return (new DocHabitacionHotelResource($docHabitacionHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHabitacionHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
