<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class DocIncidenciaHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocIncidenciaHotel::with(['establecimiento'])->select(sprintf('%s.*', (new DocIncidenciaHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_incidencia_hotel_show';
                $editGate      = 'doc_incidencia_hotel_edit';
                $deleteGate    = 'doc_incidencia_hotel_delete';
                $crudRoutePart = 'doc-incidencia-hotels';

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

            $table->editColumn('titulo', function ($row) {
                return $row->titulo ? $row->titulo : '';
            });
            $table->editColumn('estado', function ($row) {
                return $row->estado ? DocIncidenciaHotel::ESTADO_SELECT[$row->estado] : '';
            });
            $table->editColumn('dni', function ($row) {
                return $row->dni ? $row->dni : '';
            });
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('admin.docIncidenciaHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_incidencia_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.docIncidenciaHotels.create', compact('establecimientos'));
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

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-incidencia-hotels.index');
    }

    public function edit(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docIncidenciaHotel->load('establecimiento');

        return view('admin.docIncidenciaHotels.edit', compact('docIncidenciaHotel', 'establecimientos'));
    }

    public function update(UpdateDocIncidenciaHotelRequest $request, DocIncidenciaHotel $docIncidenciaHotel)
    {
        if (!$request->filled('fecha')) {
            $request->merge(['fecha' => \Carbon\Carbon::now()->format(config('panel.date_format') . ' ' . config('panel.time_format'))]);
        }
        $docIncidenciaHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-incidencia-hotels.index');
    }

    public function show(DocIncidenciaHotel $docIncidenciaHotel)
    {
        abort_if(Gate::denies('doc_incidencia_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docIncidenciaHotel->load('establecimiento');

        return view('admin.docIncidenciaHotels.show', compact('docIncidenciaHotel'));
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
