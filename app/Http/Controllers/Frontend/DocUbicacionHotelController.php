<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocUbicacionHotelRequest;
use App\Http\Requests\StoreDocUbicacionHotelRequest;
use App\Http\Requests\UpdateDocUbicacionHotelRequest;
use App\Models\DocUbicacionHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocUbicacionHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docUbicacionHotels = DocUbicacionHotel::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docUbicacionHotels.index', compact('docUbicacionHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docUbicacionHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocUbicacionHotelRequest $request)
    {
        $docUbicacionHotel = DocUbicacionHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docUbicacionHotel->id]);
        }

        return redirect()->route('frontend.doc-ubicacion-hotels.index');
    }

    public function edit(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docUbicacionHotel->load('establecimiento');

        return view('frontend.docUbicacionHotels.edit', compact('docUbicacionHotel', 'establecimientos'));
    }

    public function update(UpdateDocUbicacionHotelRequest $request, DocUbicacionHotel $docUbicacionHotel)
    {
        $docUbicacionHotel->update($request->all());

        return redirect()->route('frontend.doc-ubicacion-hotels.index');
    }

    public function show(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docUbicacionHotel->load('establecimiento');

        return view('frontend.docUbicacionHotels.show', compact('docUbicacionHotel'));
    }

    public function destroy(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docUbicacionHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocUbicacionHotelRequest $request)
    {
        $docUbicacionHotels = DocUbicacionHotel::find(request('ids'));

        foreach ($docUbicacionHotels as $docUbicacionHotel) {
            $docUbicacionHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_create') && Gate::denies('doc_ubicacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocUbicacionHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
