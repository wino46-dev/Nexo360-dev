<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDocTarifaHotelRequest;
use App\Http\Requests\StoreDocTarifaHotelRequest;
use App\Http\Requests\UpdateDocTarifaHotelRequest;
use App\Models\DocHabitacionHotel;
use App\Models\DocTarifaHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocTarifaHotelController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocTarifaHotel::with(['establecimiento', 'habitacion'])->select(sprintf('%s.*', (new DocTarifaHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_tarifa_hotel_show';
                $editGate      = 'doc_tarifa_hotel_edit';
                $deleteGate    = 'doc_tarifa_hotel_delete';
                $crudRoutePart = 'doc-tarifa-hotels';

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

            $table->editColumn('tarifa', function ($row) {
                return $row->tarifa ? $row->tarifa : '';
            });
            $table->editColumn('regimen', function ($row) {
                return $row->regimen ? DocTarifaHotel::REGIMEN_SELECT[$row->regimen] : '';
            });
            $table->addColumn('habitacion_nombre', function ($row) {
                return $row->habitacion ? $row->habitacion->nombre : '';
            });

            $table->editColumn('importe', function ($row) {
                return $row->importe ? $row->importe : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'habitacion']);

            return $table->make(true);
        }

        $establecimientos      = Establecimiento::get();
        $doc_habitacion_hotels = DocHabitacionHotel::get();

        return view('admin.docTarifaHotels.index', compact('establecimientos', 'doc_habitacion_hotels'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_tarifa_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eid = request('establecimiento_id');
        if ($eid) {
            $habitacions = DocHabitacionHotel::where('establecimiento_id', $eid)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $habitacions = DocHabitacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('admin.docTarifaHotels.create', compact('establecimientos', 'habitacions'));
    }

    public function store(StoreDocTarifaHotelRequest $request)
    {
        $docTarifaHotel = DocTarifaHotel::create($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-tarifa-hotels.index');
    }

    public function edit(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docTarifaHotel->load('establecimiento', 'habitacion');
        $eid = request('establecimiento_id') ?: $docTarifaHotel->establecimiento_id;
        if ($eid) {
            $habitacions = DocHabitacionHotel::where('establecimiento_id', $eid)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $habitacions = DocHabitacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('admin.docTarifaHotels.edit', compact('docTarifaHotel', 'establecimientos', 'habitacions'));
    }

    public function update(UpdateDocTarifaHotelRequest $request, DocTarifaHotel $docTarifaHotel)
    {
        $docTarifaHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-tarifa-hotels.index');
    }

    public function show(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docTarifaHotel->load('establecimiento', 'habitacion');

        return view('admin.docTarifaHotels.show', compact('docTarifaHotel'));
    }

    public function destroy(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docTarifaHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocTarifaHotelRequest $request)
    {
        $docTarifaHotels = DocTarifaHotel::find(request('ids'));

        foreach ($docTarifaHotels as $docTarifaHotel) {
            $docTarifaHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
