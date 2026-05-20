<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCheckInRequest;
use App\Http\Requests\UpdateCheckInRequest;
use App\Http\Resources\Admin\CheckInResource;
use App\Models\CheckIn;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckInResource(CheckIn::with(['cliente', 'reserva', 'habitacion', 'totem'])->get());
    }

    public function store(StoreCheckInRequest $request)
    {
        $checkIn = CheckIn::create($request->all());

        if ($request->input('dni_anverso', false)) {
            $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_anverso'))))->toMediaCollection('dni_anverso');
        }

        if ($request->input('dni_reverso', false)) {
            $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_reverso'))))->toMediaCollection('dni_reverso');
        }

        return (new CheckInResource($checkIn))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckInResource($checkIn->load(['cliente', 'reserva', 'habitacion', 'totem']));
    }

    public function update(UpdateCheckInRequest $request, CheckIn $checkIn)
    {
        $checkIn->update($request->all());

        if ($request->input('dni_anverso', false)) {
            if (! $checkIn->dni_anverso || $request->input('dni_anverso') !== $checkIn->dni_anverso->file_name) {
                if ($checkIn->dni_anverso) {
                    $checkIn->dni_anverso->delete();
                }
                $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_anverso'))))->toMediaCollection('dni_anverso');
            }
        } elseif ($checkIn->dni_anverso) {
            $checkIn->dni_anverso->delete();
        }

        if ($request->input('dni_reverso', false)) {
            if (! $checkIn->dni_reverso || $request->input('dni_reverso') !== $checkIn->dni_reverso->file_name) {
                if ($checkIn->dni_reverso) {
                    $checkIn->dni_reverso->delete();
                }
                $checkIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('dni_reverso'))))->toMediaCollection('dni_reverso');
            }
        } elseif ($checkIn->dni_reverso) {
            $checkIn->dni_reverso->delete();
        }

        return (new CheckInResource($checkIn))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CheckIn $checkIn)
    {
        abort_if(Gate::denies('check_in_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkIn->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
