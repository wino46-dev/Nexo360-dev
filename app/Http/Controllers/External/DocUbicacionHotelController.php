<?php

namespace App\Http\Controllers\External;

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
use Yajra\DataTables\Facades\DataTables;

class DocUbicacionHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocUbicacionHotel::with(['establecimiento'])->select(sprintf('%s.*', (new DocUbicacionHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_ubicacion_hotel_show';
                $editGate      = 'doc_ubicacion_hotel_edit';
                $deleteGate    = 'doc_ubicacion_hotel_delete';
                $crudRoutePart = 'doc-ubicacion-hotels';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('establecimiento_codigo', function ($row) {
                return $row->establecimiento ? $row->establecimiento->codigo : '';
            });

            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('tipo', function ($row) {
                return $row->tipo ? DocUbicacionHotel::TIPO_SELECT[$row->tipo] : '';
            });
            $table->editColumn('piso', function ($row) {
                return $row->piso ? $row->piso : '';
            });
            $table->editColumn('zona_comun', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->zona_comun ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'zona_comun']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('external.docUbicacionHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.docUbicacionHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocUbicacionHotelRequest $request)
    {
        $docUbicacionHotel = DocUbicacionHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docUbicacionHotel->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-ubicacion-hotels.index');
    }

    public function edit(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docUbicacionHotel->load('establecimiento');

        return view('external.docUbicacionHotels.edit', compact('docUbicacionHotel', 'establecimientos'));
    }

    public function update(UpdateDocUbicacionHotelRequest $request, DocUbicacionHotel $docUbicacionHotel)
    {
        $docUbicacionHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-ubicacion-hotels.index');
    }

    public function show(DocUbicacionHotel $docUbicacionHotel)
    {
        abort_if(Gate::denies('doc_ubicacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docUbicacionHotel->load('establecimiento', 'ubicacionDockStockHotels');

        return view('external.docUbicacionHotels.show', compact('docUbicacionHotel'));
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
