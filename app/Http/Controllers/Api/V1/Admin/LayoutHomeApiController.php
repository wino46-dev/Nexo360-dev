<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreLayoutHomeRequest;
use App\Http\Requests\UpdateLayoutHomeRequest;
use App\Http\Resources\Admin\LayoutHomeResource;
use App\Models\LayoutHome;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LayoutHomeApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('layout_home_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LayoutHomeResource(LayoutHome::with(['team'])->get());
    }

    public function store(StoreLayoutHomeRequest $request)
    {
        $layoutHome = LayoutHome::create($request->all());

        if ($request->input('imagen_1', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_1'))))->toMediaCollection('imagen_1');
        }

        if ($request->input('imagen_2', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_2'))))->toMediaCollection('imagen_2');
        }

        if ($request->input('imagen_3', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_3'))))->toMediaCollection('imagen_3');
        }

        if ($request->input('imagen_4', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_4'))))->toMediaCollection('imagen_4');
        }

        if ($request->input('imagen_5', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_5'))))->toMediaCollection('imagen_5');
        }

        return (new LayoutHomeResource($layoutHome))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LayoutHome $layoutHome)
    {
        abort_if(Gate::denies('layout_home_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LayoutHomeResource($layoutHome->load(['team']));
    }

    public function update(UpdateLayoutHomeRequest $request, LayoutHome $layoutHome)
    {
        $layoutHome->update($request->all());

        if ($request->input('imagen_1', false)) {
            if (! $layoutHome->imagen_1 || $request->input('imagen_1') !== $layoutHome->imagen_1->file_name) {
                if ($layoutHome->imagen_1) {
                    $layoutHome->imagen_1->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_1'))))->toMediaCollection('imagen_1');
            }
        } elseif ($layoutHome->imagen_1) {
            $layoutHome->imagen_1->delete();
        }

        if ($request->input('imagen_2', false)) {
            if (! $layoutHome->imagen_2 || $request->input('imagen_2') !== $layoutHome->imagen_2->file_name) {
                if ($layoutHome->imagen_2) {
                    $layoutHome->imagen_2->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_2'))))->toMediaCollection('imagen_2');
            }
        } elseif ($layoutHome->imagen_2) {
            $layoutHome->imagen_2->delete();
        }

        if ($request->input('imagen_3', false)) {
            if (! $layoutHome->imagen_3 || $request->input('imagen_3') !== $layoutHome->imagen_3->file_name) {
                if ($layoutHome->imagen_3) {
                    $layoutHome->imagen_3->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_3'))))->toMediaCollection('imagen_3');
            }
        } elseif ($layoutHome->imagen_3) {
            $layoutHome->imagen_3->delete();
        }

        if ($request->input('imagen_4', false)) {
            if (! $layoutHome->imagen_4 || $request->input('imagen_4') !== $layoutHome->imagen_4->file_name) {
                if ($layoutHome->imagen_4) {
                    $layoutHome->imagen_4->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_4'))))->toMediaCollection('imagen_4');
            }
        } elseif ($layoutHome->imagen_4) {
            $layoutHome->imagen_4->delete();
        }

        if ($request->input('imagen_5', false)) {
            if (! $layoutHome->imagen_5 || $request->input('imagen_5') !== $layoutHome->imagen_5->file_name) {
                if ($layoutHome->imagen_5) {
                    $layoutHome->imagen_5->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_5'))))->toMediaCollection('imagen_5');
            }
        } elseif ($layoutHome->imagen_5) {
            $layoutHome->imagen_5->delete();
        }

        return (new LayoutHomeResource($layoutHome))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LayoutHome $layoutHome)
    {
        abort_if(Gate::denies('layout_home_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $layoutHome->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
