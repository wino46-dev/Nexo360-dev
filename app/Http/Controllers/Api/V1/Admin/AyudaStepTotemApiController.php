<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAyudaStepTotemRequest;
use App\Http\Requests\UpdateAyudaStepTotemRequest;
use App\Http\Resources\Admin\AyudaStepTotemResource;
use App\Models\AyudaStepTotem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AyudaStepTotemApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ayuda_step_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AyudaStepTotemResource(AyudaStepTotem::with(['establecimiento'])->get());
    }

    public function store(StoreAyudaStepTotemRequest $request)
    {
        $ayudaStepTotem = AyudaStepTotem::create($request->all());

        return (new AyudaStepTotemResource($ayudaStepTotem))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AyudaStepTotem $ayudaStepTotem)
    {
        abort_if(Gate::denies('ayuda_step_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AyudaStepTotemResource($ayudaStepTotem->load(['establecimiento']));
    }

    public function update(UpdateAyudaStepTotemRequest $request, AyudaStepTotem $ayudaStepTotem)
    {
        $ayudaStepTotem->update($request->all());

        return (new AyudaStepTotemResource($ayudaStepTotem))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AyudaStepTotem $ayudaStepTotem)
    {
        abort_if(Gate::denies('ayuda_step_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ayudaStepTotem->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
