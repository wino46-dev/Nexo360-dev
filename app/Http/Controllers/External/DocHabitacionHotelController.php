<?php

namespace App\Http\Controllers\External;

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
use Yajra\DataTables\Facades\DataTables;

class DocHabitacionHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocHabitacionHotel::with(['establecimiento'])->select(sprintf('%s.*', (new DocHabitacionHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_habitacion_hotel_show';
                $editGate      = 'doc_habitacion_hotel_edit';
                $deleteGate    = 'doc_habitacion_hotel_delete';
                $crudRoutePart = 'doc-habitacion-hotels';

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
                return $row->tipo ? DocHabitacionHotel::TIPO_SELECT[$row->tipo] : '';
            });
            $table->editColumn('capacidad', function ($row) {
                return $row->capacidad ? DocHabitacionHotel::CAPACIDAD_SELECT[$row->capacidad] : '';
            });
            $table->editColumn('tipo_cerradura', function ($row) {
                return $row->tipo_cerradura ? DocHabitacionHotel::TIPO_CERRADURA_SELECT[$row->tipo_cerradura] : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('external.docHabitacionHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_habitacion_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.docHabitacionHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocHabitacionHotelRequest $request)
    {
        $docHabitacionHotel = DocHabitacionHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHabitacionHotel->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-habitacion-hotels.index');
    }

    public function edit(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHabitacionHotel->load('establecimiento');

        return view('external.docHabitacionHotels.edit', compact('docHabitacionHotel', 'establecimientos'));
    }

    public function update(UpdateDocHabitacionHotelRequest $request, DocHabitacionHotel $docHabitacionHotel)
    {
        $docHabitacionHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-habitacion-hotels.index');
    }

    public function show(DocHabitacionHotel $docHabitacionHotel)
    {
        abort_if(Gate::denies('doc_habitacion_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHabitacionHotel->load('establecimiento', 'habitacionDocTarifaHotels');

        return view('external.docHabitacionHotels.show', compact('docHabitacionHotel'));
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
