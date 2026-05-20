<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSociedadRequest;
use App\Http\Requests\UpdateSociedadRequest;
use App\Http\Resources\Admin\SociedadResource;
use App\Models\Sociedad;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SociedadApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sociedad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SociedadResource(Sociedad::with(['team'])->get());
    }

    public function store(StoreSociedadRequest $request)
    {
        $sociedad = Sociedad::create($request->all());

        return (new SociedadResource($sociedad))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Sociedad $sociedad)
    {
        abort_if(Gate::denies('sociedad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SociedadResource($sociedad->load(['team']));
    }

    public function update(UpdateSociedadRequest $request, Sociedad $sociedad)
    {
        $sociedad->update($request->all());

        return (new SociedadResource($sociedad))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Sociedad $sociedad)
    {
        abort_if(Gate::denies('sociedad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedad->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
