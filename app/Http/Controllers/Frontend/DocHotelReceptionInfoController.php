<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocHotelReceptionInfoRequest;
use App\Http\Requests\StoreDocHotelReceptionInfoRequest;
use App\Http\Requests\UpdateDocHotelReceptionInfoRequest;
use App\Models\DocHotelReceptionInfo;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocHotelReceptionInfoController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_hotel_reception_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelReceptionInfos = DocHotelReceptionInfo::with(['establecimiento', 'media'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docHotelReceptionInfos.index', compact('docHotelReceptionInfos', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_hotel_reception_info_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docHotelReceptionInfos.create', compact('establecimientos'));
    }

    public function store(StoreDocHotelReceptionInfoRequest $request)
    {
        $docHotelReceptionInfo = DocHotelReceptionInfo::create($request->all());

        foreach ($request->input('box_photo', []) as $file) {
            $docHotelReceptionInfo->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('box_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHotelReceptionInfo->id]);
        }

        return redirect()->route('frontend.doc-hotel-reception-infos.index');
    }

    public function edit(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHotelReceptionInfo->load('establecimiento');

        return view('frontend.docHotelReceptionInfos.edit', compact('docHotelReceptionInfo', 'establecimientos'));
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

        return redirect()->route('frontend.doc-hotel-reception-infos.index');
    }

    public function show(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelReceptionInfo->load('establecimiento');

        return view('frontend.docHotelReceptionInfos.show', compact('docHotelReceptionInfo'));
    }

    public function destroy(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelReceptionInfo->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocHotelReceptionInfoRequest $request)
    {
        $docHotelReceptionInfos = DocHotelReceptionInfo::find(request('ids'));

        foreach ($docHotelReceptionInfos as $docHotelReceptionInfo) {
            $docHotelReceptionInfo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_create') && Gate::denies('doc_hotel_reception_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocHotelReceptionInfo();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
