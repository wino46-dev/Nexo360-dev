<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaiRequest;
use App\Http\Requests\UpdatePaiRequest;
use App\Http\Resources\Admin\PaiResource;
use App\Models\Pai;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaisApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pai_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PaiResource(Pai::with(['team'])->get());
    }

    public function store(StorePaiRequest $request)
    {
        $pai = Pai::create($request->all());

        return (new PaiResource($pai))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pai $pai)
    {
        abort_if(Gate::denies('pai_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PaiResource($pai->load(['team']));
    }

    public function update(UpdatePaiRequest $request, Pai $pai)
    {
        $pai->update($request->all());

        return (new PaiResource($pai))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Pai $pai)
    {
        abort_if(Gate::denies('pai_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pai->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
