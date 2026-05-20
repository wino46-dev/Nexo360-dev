<?php

namespace App\Http\Controllers\Frontend;

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

class DockStockHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('dock_stock_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotels = DockStockHotel::with(['ubicacion'])->get();

        $doc_ubicacion_hotels = DocUbicacionHotel::get();

        return view('frontend.dockStockHotels.index', compact('doc_ubicacion_hotels', 'dockStockHotels'));
    }

    public function create()
    {
        abort_if(Gate::denies('dock_stock_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ubicacions = DocUbicacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.dockStockHotels.create', compact('ubicacions'));
    }

    public function store(StoreDockStockHotelRequest $request)
    {
        $dockStockHotel = DockStockHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $dockStockHotel->id]);
        }

        return redirect()->route('frontend.dock-stock-hotels.index');
    }

    public function edit(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ubicacions = DocUbicacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dockStockHotel->load('ubicacion');

        return view('frontend.dockStockHotels.edit', compact('dockStockHotel', 'ubicacions'));
    }

    public function update(UpdateDockStockHotelRequest $request, DockStockHotel $dockStockHotel)
    {
        $dockStockHotel->update($request->all());

        return redirect()->route('frontend.dock-stock-hotels.index');
    }

    public function show(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotel->load('ubicacion');

        return view('frontend.dockStockHotels.show', compact('dockStockHotel'));
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
