<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocHabitacionHotelRequest;
use App\Http\Requests\StoreDocHabitacionHotelRequest;
use App\Http\Requests\UpdateDocHabitacionHotelRequest;
use App\Models\DocHabitacionHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocHabitacionHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_habitacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHabitacionHotels = DocHabitacionHotel::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docHabitacionHotels.index', compact('docHabitacionHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_habitacion_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docHabitacionHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocHabitacionHotelRequest $request)
    {
        $docHabitacionHotel = DocHabitacionHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHabitacionHotel->id]);
        }

        return redirect()->route('frontend.doc-habitacion-hotels.index');
    }

    public function edit(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHabitacionHotel->load('establecimiento');

        return view('frontend.docHabitacionHotels.edit', compact('docHabitacionHotel', 'establecimientos'));
    }

    public function update(UpdateDocHabitacionHotelRequest $request, DocHabitacionHotel $docHabitacionHotel)
    {
        $docHabitacionHotel->update($request->all());

        return redirect()->route('frontend.doc-habitacion-hotels.index');
    }

    public function show(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHabitacionHotel->load('establecimiento');

        return view('frontend.docHabitacionHotels.show', compact('docHabitacionHotel'));
    }

    public function destroy(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHabitacionHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocHabitacionHotelRequest $request)
    {
        $docHabitacionHotels = DocHabitacionHotel::find(request('ids'));

        foreach ($docHabitacionHotels as $docHabitacionHotel) {
            $docHabitacionHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_create') && Gate::denies('doc_habitacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocHabitacionHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
