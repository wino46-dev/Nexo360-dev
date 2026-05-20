<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDockStockHotelRequest;
use App\Http\Requests\UpdateDockStockHotelRequest;
use App\Http\Resources\Admin\DockStockHotelResource;
use App\Models\DockStockHotel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DockStockHotelApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('dock_stock_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DockStockHotelResource(DockStockHotel::with(['ubicacion'])->get());
    }

    public function store(StoreDockStockHotelRequest $request)
    {
        $dockStockHotel = DockStockHotel::create($request->all());

        return (new DockStockHotelResource($dockStockHotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DockStockHotelResource($dockStockHotel->load(['ubicacion']));
    }

    public function update(UpdateDockStockHotelRequest $request, DockStockHotel $dockStockHotel)
    {
        $dockStockHotel->update($request->all());

        return (new DockStockHotelResource($dockStockHotel))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DockStockHotel $dockStockHotel)
    {
        abort_if(Gate::denies('dock_stock_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dockStockHotel->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
