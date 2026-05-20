<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocHotelEstadoCajaRequest;
use App\Http\Requests\UpdateDocHotelEstadoCajaRequest;
use App\Http\Resources\Admin\DocHotelEstadoCajaResource;
use App\Models\DocHotelEstadoCaja;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocHotelEstadoCajaApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHotelEstadoCajaResource(DocHotelEstadoCaja::with(['establecimiento', 'room'])->get());
    }

    public function store(StoreDocHotelEstadoCajaRequest $request)
    {
        $docHotelEstadoCaja = DocHotelEstadoCaja::create($request->all());

        return (new DocHotelEstadoCajaResource($docHotelEstadoCaja))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHotelEstadoCajaResource($docHotelEstadoCaja->load(['establecimiento', 'room']));
    }

    public function update(UpdateDocHotelEstadoCajaRequest $request, DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        $docHotelEstadoCaja->update($request->all());

        return (new DocHotelEstadoCajaResource($docHotelEstadoCaja))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelEstadoCaja->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
