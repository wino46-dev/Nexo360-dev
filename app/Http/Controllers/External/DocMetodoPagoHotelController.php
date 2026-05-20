<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDocMetodoPagoHotelRequest;
use App\Http\Requests\StoreDocMetodoPagoHotelRequest;
use App\Http\Requests\UpdateDocMetodoPagoHotelRequest;
use App\Models\DocMetodoPagoHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocMetodoPagoHotelController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocMetodoPagoHotel::with(['establecimiento'])->select(sprintf('%s.*', (new DocMetodoPagoHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_metodo_pago_hotel_show';
                $editGate      = 'doc_metodo_pago_hotel_edit';
                $deleteGate    = 'doc_metodo_pago_hotel_delete';
                $crudRoutePart = 'doc-metodo-pago-hotels';

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
                return $row->tipo ? DocMetodoPagoHotel::TIPO_SELECT[$row->tipo] : '';
            });
            $table->editColumn('activo', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->activo ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'activo']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('external.docMetodoPagoHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.docMetodoPagoHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocMetodoPagoHotelRequest $request)
    {
        $docMetodoPagoHotel = DocMetodoPagoHotel::create($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-metodo-pago-hotels.index');
    }

    public function edit(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docMetodoPagoHotel->load('establecimiento');

        return view('external.docMetodoPagoHotels.edit', compact('docMetodoPagoHotel', 'establecimientos'));
    }

    public function update(UpdateDocMetodoPagoHotelRequest $request, DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        $docMetodoPagoHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-metodo-pago-hotels.index');
    }

    public function show(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docMetodoPagoHotel->load('establecimiento');

        return view('external.docMetodoPagoHotels.show', compact('docMetodoPagoHotel'));
    }

    public function destroy(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docMetodoPagoHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocMetodoPagoHotelRequest $request)
    {
        $docMetodoPagoHotels = DocMetodoPagoHotel::find(request('ids'));

        foreach ($docMetodoPagoHotels as $docMetodoPagoHotel) {
            $docMetodoPagoHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
