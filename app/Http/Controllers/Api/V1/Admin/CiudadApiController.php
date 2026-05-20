<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCiudadRequest;
use App\Http\Requests\UpdateCiudadRequest;
use App\Http\Resources\Admin\CiudadResource;
use App\Models\Ciudad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CiudadApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ciudad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CiudadResource(Ciudad::with(['provincia'])->get());
    }

    public function store(StoreCiudadRequest $request)
    {
        $ciudad = Ciudad::create($request->all());

        return (new CiudadResource($ciudad))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CiudadResource($ciudad->load(['provincia']));
    }

    public function update(UpdateCiudadRequest $request, Ciudad $ciudad)
    {
        $ciudad->update($request->all());

        return (new CiudadResource($ciudad))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ciudad $ciudad)
    {
        abort_if(Gate::denies('ciudad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ciudad->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
