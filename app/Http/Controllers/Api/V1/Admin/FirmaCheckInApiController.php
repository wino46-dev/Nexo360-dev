<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreFirmaCheckInRequest;
use App\Http\Requests\UpdateFirmaCheckInRequest;
use App\Http\Resources\Admin\FirmaCheckInResource;
use App\Models\FirmaCheckIn;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FirmaCheckInApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('firma_check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FirmaCheckInResource(FirmaCheckIn::with(['sesion'])->get());
    }

    public function store(StoreFirmaCheckInRequest $request)
    {
        $firmaCheckIn = FirmaCheckIn::create($request->all());

        if ($request->input('documento', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('documento'))))->toMediaCollection('documento');
        }

        if ($request->input('firma_imagen', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_imagen'))))->toMediaCollection('firma_imagen');
        }

        return (new FirmaCheckInResource($firmaCheckIn))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FirmaCheckInResource($firmaCheckIn->load(['sesion']));
    }

    public function update(UpdateFirmaCheckInRequest $request, FirmaCheckIn $firmaCheckIn)
    {
        $firmaCheckIn->update($request->all());

        if ($request->input('documento', false)) {
            if (! $firmaCheckIn->documento || $request->input('documento') !== $firmaCheckIn->documento->file_name) {
                if ($firmaCheckIn->documento) {
                    $firmaCheckIn->documento->delete();
                }
                $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('documento'))))->toMediaCollection('documento');
            }
        } elseif ($firmaCheckIn->documento) {
            $firmaCheckIn->documento->delete();
        }

        if ($request->input('firma_imagen', false)) {
            if (! $firmaCheckIn->firma_imagen || $request->input('firma_imagen') !== $firmaCheckIn->firma_imagen->file_name) {
                if ($firmaCheckIn->firma_imagen) {
                    $firmaCheckIn->firma_imagen->delete();
                }
                $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_imagen'))))->toMediaCollection('firma_imagen');
            }
        } elseif ($firmaCheckIn->firma_imagen) {
            $firmaCheckIn->firma_imagen->delete();
        }

        return (new FirmaCheckInResource($firmaCheckIn))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
