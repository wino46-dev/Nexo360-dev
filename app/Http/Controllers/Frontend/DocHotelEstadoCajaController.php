<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocHotelEstadoCajaRequest;
use App\Http\Requests\StoreDocHotelEstadoCajaRequest;
use App\Http\Requests\UpdateDocHotelEstadoCajaRequest;
use App\Models\DocHotelEstadoCaja;
use App\Models\Establecimiento;
use App\Models\Habitacion;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DocHotelEstadoCajaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelEstadoCajas = DocHotelEstadoCaja::with(['establecimiento', 'room'])->get();

        $establecimientos = Establecimiento::get();

        $habitacions = Habitacion::get();

        return view('frontend.docHotelEstadoCajas.index', compact('docHotelEstadoCajas', 'establecimientos', 'habitacions'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rooms = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docHotelEstadoCajas.create', compact('establecimientos', 'rooms'));
    }

    public function store(StoreDocHotelEstadoCajaRequest $request)
    {
        $docHotelEstadoCaja = DocHotelEstadoCaja::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHotelEstadoCaja->id]);
        }

        return redirect()->route('frontend.doc-hotel-estado-cajas.index');
    }

    public function edit(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rooms = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHotelEstadoCaja->load('establecimiento', 'room');

        return view('frontend.docHotelEstadoCajas.edit', compact('docHotelEstadoCaja', 'establecimientos', 'rooms'));
    }

    public function update(UpdateDocHotelEstadoCajaRequest $request, DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        $docHotelEstadoCaja->update($request->all());

        return redirect()->route('frontend.doc-hotel-estado-cajas.index');
    }

    public function show(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelEstadoCaja->load('establecimiento', 'room');

        return view('frontend.docHotelEstadoCajas.show', compact('docHotelEstadoCaja'));
    }

    public function destroy(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelEstadoCaja->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocHotelEstadoCajaRequest $request)
    {
        $docHotelEstadoCajas = DocHotelEstadoCaja::find(request('ids'));

        foreach ($docHotelEstadoCajas as $docHotelEstadoCaja) {
            $docHotelEstadoCaja->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_create') && Gate::denies('doc_hotel_estado_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocHotelEstadoCaja();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
