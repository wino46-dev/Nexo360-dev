<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDockStockHotelRequest;
use App\Http\Requests\StoreDockStockHotelRequest;
use App\Http\Requests\UpdateDockStockHotelRequest;
use App\Models\DockStockHotel;
use App\Models\DocUbicacionHotel;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DockStockHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('dock_stock_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DockStockHotel::with(['ubicacion'])->select(sprintf('%s.*', (new DockStockHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $eid = $request->get('establecimiento_id');
                $query->whereHas('ubicacion', function ($q) use ($eid) {
                    $q->where('establecimiento_id', $eid);
                });
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'dock_stock_hotel_show';
                $editGate      = 'dock_stock_hotel_edit';
                $deleteGate    = 'dock_stock_hotel_delete';
                $crudRoutePart = 'dock-stock-hotels';

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
            $table->editColumn('nombre', function ($row) {
                return $row->nombre ? $row->nombre : '';
            });
            $table->addColumn('ubicacion_nombre', function ($row) {
                return $row->ubicacion ? $row->ubicacion->nombre : '';
            });

            $table->editColumn('cantidad', function ($row) {
                return $row->cantidad ? $row->cantidad : '';
            });
            $table->editColumn('unidad', function ($row) {
                return $row->unidad ? DockStockHotel::UNIDAD_SELECT[$row->unidad] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'ubicacion']);

            return $table->make(true);
        }

        $doc_ubicacion_hotels = DocUbicacionHotel::get();

        return view('external.dockStockHotels.index', compact('doc_ubicacion_hotels'));
    }

    public function create()
    {
        abort_if(Gate::denies('dock_stock_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eid = request('establecimiento_id');
        if ($eid) {
            $ubicacions = DocUbicacionHotel::where('establecimiento_id', $eid)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $ubicacions = DocUbicacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('external.dockStockHotels.create', compact('ubicacions'));
    }

    public function store(StoreDockStockHotelRequest $request)
    {
        $dockStockHotel = DockStockHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dockStockHotel->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.dock-stock-hotels.index');
    }

    public function edit(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotel->load('ubicacion');
        $eid = request('establecimiento_id') ?: optional($dockStockHotel->ubicacion)->establecimiento_id;
        if ($eid) {
            $ubicacions = DocUbicacionHotel::where('establecimiento_id', $eid)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        } else {
            $ubicacions = DocUbicacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        }

        return view('external.dockStockHotels.edit', compact('dockStockHotel', 'ubicacions'));
    }

    public function update(UpdateDockStockHotelRequest $request, DockStockHotel $dockStockHotel)
    {
        $dockStockHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.dock-stock-hotels.index');
    }

    public function show(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotel->load('ubicacion');

        return view('external.dockStockHotels.show', compact('dockStockHotel'));
    }

    public function destroy(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDockStockHotelRequest $request)
    {
        $dockStockHotels = DockStockHotel::find(request('ids'));

        foreach ($dockStockHotels as $dockStockHotel) {
            $dockStockHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('dock_stock_hotel_create') && Gate::denies('dock_stock_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DockStockHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
