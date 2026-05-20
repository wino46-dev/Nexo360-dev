<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;
use App\Http\Resources\Admin\EstablecimientoResource;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecimientoApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('establecimiento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EstablecimientoResource(Establecimiento::with(['sociedad'])->get());
    }

    public function store(StoreEstablecimientoRequest $request)
    {
        $establecimiento = Establecimiento::create($request->all());

        if ($request->input('logo_establecimiento', false)) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_establecimiento'))))->toMediaCollection('logo_establecimiento');
        }

        foreach ($request->input('imagenes', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        foreach ($request->input('tour_images', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('tour_images');
        }

        return (new EstablecimientoResource($establecimiento))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new EstablecimientoResource($establecimiento->load(['sociedad']));
    }

    public function update(UpdateEstablecimientoRequest $request, Establecimiento $establecimiento)
    {
        $establecimiento->update($request->all());

        if ($request->input('logo_establecimiento', false)) {
            if (! $establecimiento->logo_establecimiento || $request->input('logo_establecimiento') !== $establecimiento->logo_establecimiento->file_name) {
                if ($establecimiento->logo_establecimiento) {
                    $establecimiento->logo_establecimiento->delete();
                }
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo_establecimiento'))))->toMediaCollection('logo_establecimiento');
            }
        } elseif ($establecimiento->logo_establecimiento) {
            $establecimiento->logo_establecimiento->delete();
        }

        if (count($establecimiento->imagenes) > 0) {
            foreach ($establecimiento->imagenes as $media) {
                if (! in_array($media->file_name, $request->input('imagenes', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->imagenes->pluck('file_name')->toArray();
        foreach ($request->input('imagenes', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
            }
        }

        if (count($establecimiento->tour_images) > 0) {
            foreach ($establecimiento->tour_images as $media) {
                if (! in_array($media->file_name, $request->input('tour_images', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->tour_images->pluck('file_name')->toArray();
        foreach ($request->input('tour_images', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('tour_images');
            }
        }

        return (new EstablecimientoResource($establecimiento))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimiento->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
