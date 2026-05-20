<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProvinciumRequest;
use App\Http\Requests\UpdateProvinciumRequest;
use App\Http\Resources\Admin\ProvinciumResource;
use App\Models\Provincium;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvinciaApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('provincium_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProvinciumResource(Provincium::with(['pais'])->get());
    }

    public function store(StoreProvinciumRequest $request)
    {
        $provincium = Provincium::create($request->all());

        return (new ProvinciumResource($provincium))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProvinciumResource($provincium->load(['pais']));
    }

    public function update(UpdateProvinciumRequest $request, Provincium $provincium)
    {
        $provincium->update($request->all());

        return (new ProvinciumResource($provincium))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Provincium $provincium)
    {
        abort_if(Gate::denies('provincium_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $provincium->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
