<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocIncidenciaHotelRequest;
use App\Http\Requests\StoreDocIncidenciaHotelRequest;
use App\Http\Requests\UpdateDocIncidenciaHotelRequest;
use App\Models\DocIncidenciaHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocIncidenciaHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_incidencia_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docIncidenciaHotels = DocIncidenciaHotel::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docIncidenciaHotels.index', compact('docIncidenciaHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_incidencia_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docIncidenciaHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocIncidenciaHotelRequest $request)
    {
        if (!$request->filled('fecha')) {
            $request->merge(['fecha' => \Carbon\Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'))]);
        }
        $docIncidenciaHotel = DocIncidenciaHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docIncidenciaHotel->id]);
        }

        return redirect()->route('frontend.doc-incidencia-hotels.index');
    }

    public function edit(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docIncidenciaHotel->load('establecimiento');

        return view('frontend.docIncidenciaHotels.edit', compact('docIncidenciaHotel', 'establecimientos'));
    }

    public function update(UpdateDocIncidenciaHotelRequest $request, DocIncidenciaHotel $docIncidenciaHotel)
    {
        if (!$request->filled('fecha')) {
            $request->merge(['fecha' => \Carbon\Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'))]);
        }
        $docIncidenciaHotel->update($request->all());

        return redirect()->route('frontend.doc-incidencia-hotels.index');
    }

    public function show(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docIncidenciaHotel->load('establecimiento');

        return view('frontend.docIncidenciaHotels.show', compact('docIncidenciaHotel'));
    }

    public function destroy(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docIncidenciaHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocIncidenciaHotelRequest $request)
    {
        $docIncidenciaHotels = DocIncidenciaHotel::find(request('ids'));

        foreach ($docIncidenciaHotels as $docIncidenciaHotel) {
            $docIncidenciaHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_create') && Gate::denies('doc_incidencia_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocIncidenciaHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
