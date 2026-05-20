<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocInfoHotelRequest;
use App\Http\Requests\StoreDocInfoHotelRequest;
use App\Http\Requests\UpdateDocInfoHotelRequest;
use App\Models\Ciudad;
use App\Models\DocInfoHotel;
use App\Models\Establecimiento;
use App\Models\Pai;
use App\Models\Provincium;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocInfoHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_info_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docInfoHotels = DocInfoHotel::with(['hotel', 'pais', 'provincia', 'ciudad'])->get();

        $establecimientos = Establecimiento::get();

        $pais = Pai::get();

        $provincia = Provincium::get();

        $ciudads = Ciudad::get();

        return view('frontend.docInfoHotels.index', compact('ciudads', 'docInfoHotels', 'establecimientos', 'pais', 'provincia'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_info_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docInfoHotels.create', compact('ciudads', 'hotels', 'pais', 'provincias'));
    }

    public function store(StoreDocInfoHotelRequest $request)
    {
        $docInfoHotel = DocInfoHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docInfoHotel->id]);
        }

        return redirect()->route('frontend.doc-info-hotels.index');
    }

    public function edit(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docInfoHotel->load('hotel', 'pais', 'provincia', 'ciudad');

        return view('frontend.docInfoHotels.edit', compact('ciudads', 'docInfoHotel', 'hotels', 'pais', 'provincias'));
    }

    public function update(UpdateDocInfoHotelRequest $request, DocInfoHotel $docInfoHotel)
    {
        $docInfoHotel->update($request->all());

        return redirect()->route('frontend.doc-info-hotels.index');
    }

    public function show(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docInfoHotel->load('hotel', 'pais', 'provincia', 'ciudad');

        return view('frontend.docInfoHotels.show', compact('docInfoHotel'));
    }

    public function destroy(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docInfoHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocInfoHotelRequest $request)
    {
        $docInfoHotels = DocInfoHotel::find(request('ids'));

        foreach ($docInfoHotels as $docInfoHotel) {
            $docInfoHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_info_hotel_create') && Gate::denies('doc_info_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocInfoHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
