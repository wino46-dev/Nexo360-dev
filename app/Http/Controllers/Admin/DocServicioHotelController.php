<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDocServicioHotelRequest;
use App\Http\Requests\StoreDocServicioHotelRequest;
use App\Http\Requests\UpdateDocServicioHotelRequest;
use App\Models\DocServicioHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocServicioHotelController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_servicio_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocServicioHotel::with(['hotel'])->select(sprintf('%s.*', (new DocServicioHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('hotel_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_servicio_hotel_show';
                $editGate      = 'doc_servicio_hotel_edit';
                $deleteGate    = 'doc_servicio_hotel_delete';
                $crudRoutePart = 'doc-servicio-hotels';

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
            $table->addColumn('hotel_codigo', function ($row) {
                return $row->hotel ? $row->hotel->codigo : '';
            });

            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('precio', function ($row) {
                return $row->precio ? $row->precio : '';
            });
            $table->editColumn('por_persona', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->por_persona ? 'checked' : null) . '>';
            });
            $table->editColumn('por_dia', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->por_dia ? 'checked' : null) . '>';
            });
            $table->editColumn('tipo', function ($row) {
                return $row->tipo ? DocServicioHotel::TIPO_SELECT[$row->tipo] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'hotel', 'por_persona', 'por_dia']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('admin.docServicioHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_servicio_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.docServicioHotels.create', compact('hotels'));
    }

    public function store(StoreDocServicioHotelRequest $request)
    {
        $docServicioHotel = DocServicioHotel::create($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-servicio-hotels.index');
    }

    public function edit(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docServicioHotel->load('hotel');

        return view('admin.docServicioHotels.edit', compact('docServicioHotel', 'hotels'));
    }

    public function update(UpdateDocServicioHotelRequest $request, DocServicioHotel $docServicioHotel)
    {
        $docServicioHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-servicio-hotels.index');
    }

    public function show(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docServicioHotel->load('hotel');

        return view('admin.docServicioHotels.show', compact('docServicioHotel'));
    }

    public function destroy(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docServicioHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocServicioHotelRequest $request)
    {
        $docServicioHotels = DocServicioHotel::find(request('ids'));

        foreach ($docServicioHotels as $docServicioHotel) {
            $docServicioHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
