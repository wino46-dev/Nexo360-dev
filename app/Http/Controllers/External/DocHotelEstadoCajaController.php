<?php

namespace App\Http\Controllers\External;

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
use Yajra\DataTables\Facades\DataTables;

class DocHotelEstadoCajaController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocHotelEstadoCaja::with(['establecimiento', 'room'])->select(sprintf('%s.*', (new DocHotelEstadoCaja)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_hotel_estado_caja_show';
                $editGate      = 'doc_hotel_estado_caja_edit';
                $deleteGate    = 'doc_hotel_estado_caja_delete';
                $crudRoutePart = 'doc-hotel-estado-cajas';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('establecimiento_codigo', function ($row) {
                return $row->establecimiento ? $row->establecimiento->codigo : '';
            });

            $table->editColumn('caja', function ($row) {
                return $row->caja ? $row->caja : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->addColumn('room_codigo', function ($row) {
                return $row->room ? $row->room->codigo : '';
            });

            $table->editColumn('room_status', function ($row) {
                return $row->room_status ? DocHotelEstadoCaja::ROOM_STATUS_SELECT[$row->room_status] : '';
            });
            $table->editColumn('cliente', function ($row) {
                return $row->cliente ? $row->cliente : '';
            });
            $table->editColumn('documento_cliente', function ($row) {
                return $row->documento_cliente ? $row->documento_cliente : '';
            });
            $table->editColumn('pay', function ($row) {
                return $row->pay ? DocHotelEstadoCaja::PAY_SELECT[$row->pay] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'room']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();
        $habitacions      = Habitacion::get();

        return view('external.docHotelEstadoCajas.index', compact('establecimientos', 'habitacions'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eid = request('establecimiento_id');
        if ($eid) {
            $rooms = Habitacion::where('establecimiento_id', $eid)->pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $rooms = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('external.docHotelEstadoCajas.create', compact('establecimientos', 'rooms'));
    }

    public function store(StoreDocHotelEstadoCajaRequest $request)
    {
        $docHotelEstadoCaja = DocHotelEstadoCaja::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHotelEstadoCaja->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-hotel-estado-cajas.index');
    }

    public function edit(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHotelEstadoCaja->load('establecimiento', 'room');
        $eid = request('establecimiento_id') ?: $docHotelEstadoCaja->establecimiento_id;
        if ($eid) {
            $rooms = Habitacion::where('establecimiento_id', $eid)->pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $rooms = Habitacion::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('external.docHotelEstadoCajas.edit', compact('docHotelEstadoCaja', 'establecimientos', 'rooms'));
    }

    public function update(UpdateDocHotelEstadoCajaRequest $request, DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        $docHotelEstadoCaja->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-hotel-estado-cajas.index');
    }

    public function show(DocHotelEstadoCaja $docHotelEstadoCaja)
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelEstadoCaja->load('establecimiento', 'room');

        return view('external.docHotelEstadoCajas.show', compact('docHotelEstadoCaja'));
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
