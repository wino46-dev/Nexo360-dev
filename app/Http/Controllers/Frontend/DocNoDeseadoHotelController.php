<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocNoDeseadoHotelRequest;
use App\Http\Requests\StoreDocNoDeseadoHotelRequest;
use App\Http\Requests\UpdateDocNoDeseadoHotelRequest;
use App\Models\DocNoDeseadoHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocNoDeseadoHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotels = DocNoDeseadoHotel::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docNoDeseadoHotels.index', compact('docNoDeseadoHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docNoDeseadoHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocNoDeseadoHotelRequest $request)
    {
        $docNoDeseadoHotel = DocNoDeseadoHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docNoDeseadoHotel->id]);
        }

        return redirect()->route('frontend.doc-no-deseado-hotels.index');
    }

    public function edit(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docNoDeseadoHotel->load('establecimiento');

        return view('frontend.docNoDeseadoHotels.edit', compact('docNoDeseadoHotel', 'establecimientos'));
    }

    public function update(UpdateDocNoDeseadoHotelRequest $request, DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        $docNoDeseadoHotel->update($request->all());

        return redirect()->route('frontend.doc-no-deseado-hotels.index');
    }

    public function show(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotel->load('establecimiento');

        return view('frontend.docNoDeseadoHotels.show', compact('docNoDeseadoHotel'));
    }

    public function destroy(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocNoDeseadoHotelRequest $request)
    {
        $docNoDeseadoHotels = DocNoDeseadoHotel::find(request('ids'));

        foreach ($docNoDeseadoHotels as $docNoDeseadoHotel) {
            $docNoDeseadoHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_create') && Gate::denies('doc_no_deseado_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocNoDeseadoHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
