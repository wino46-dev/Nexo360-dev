<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreControlErrorRequest;
use App\Http\Resources\Admin\ControlErrorResource;
use App\Models\ControlError;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlErrorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('control_error_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ControlErrorResource(ControlError::with(['team'])->get());
    }

    public function store(StoreControlErrorRequest $request)
    {
        abort_if(Gate::denies('control_error_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $controlError = ControlError::create($request->all());

        return (new ControlErrorResource($controlError))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ControlErrorResource($controlError->load(['team']));
    }

    public function destroy(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlError->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
