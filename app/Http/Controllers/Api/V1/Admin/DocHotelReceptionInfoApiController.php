<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDocHotelReceptionInfoRequest;
use App\Http\Requests\UpdateDocHotelReceptionInfoRequest;
use App\Http\Resources\Admin\DocHotelReceptionInfoResource;
use App\Models\DocHotelReceptionInfo;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocHotelReceptionInfoApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_hotel_reception_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHotelReceptionInfoResource(DocHotelReceptionInfo::with(['establecimiento'])->get());
    }

    public function store(StoreDocHotelReceptionInfoRequest $request)
    {
        $docHotelReceptionInfo = DocHotelReceptionInfo::create($request->all());

        foreach ($request->input('box_photo', []) as $file) {
            $docHotelReceptionInfo->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('box_photo');
        }

        return (new DocHotelReceptionInfoResource($docHotelReceptionInfo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DocHotelReceptionInfoResource($docHotelReceptionInfo->load(['establecimiento']));
    }

    public function update(UpdateDocHotelReceptionInfoRequest $request, DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        $docHotelReceptionInfo->update($request->all());

        if (count($docHotelReceptionInfo->box_photo) > 0) {
            foreach ($docHotelReceptionInfo->box_photo as $media) {
                if (! in_array($media->file_name, $request->input('box_photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $docHotelReceptionInfo->box_photo->pluck('file_name')->toArray();
        foreach ($request->input('box_photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $docHotelReceptionInfo->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('box_photo');
            }
        }

        return (new DocHotelReceptionInfoResource($docHotelReceptionInfo))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelReceptionInfo->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
