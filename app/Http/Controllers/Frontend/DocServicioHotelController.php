<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDocServicioHotelRequest;
use App\Http\Requests\StoreDocServicioHotelRequest;
use App\Http\Requests\UpdateDocServicioHotelRequest;
use App\Models\DocServicioHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DocServicioHotelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doc_servicio_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docServicioHotels = DocServicioHotel::with(['hotel'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docServicioHotels.index', compact('docServicioHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_servicio_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docServicioHotels.create', compact('hotels'));
    }

    public function store(StoreDocServicioHotelRequest $request)
    {
        $docServicioHotel = DocServicioHotel::create($request->all());

        return redirect()->route('frontend.doc-servicio-hotels.index');
    }

    public function edit(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docServicioHotel->load('hotel');

        return view('frontend.docServicioHotels.edit', compact('docServicioHotel', 'hotels'));
    }

    public function update(UpdateDocServicioHotelRequest $request, DocServicioHotel $docServicioHotel)
    {
        $docServicioHotel->update($request->all());

        return redirect()->route('frontend.doc-servicio-hotels.index');
    }

    public function show(DocServicioHotel $docServicioHotel)
    {
        abort_if(Gate::denies('doc_servicio_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docServicioHotel->load('hotel');

        return view('frontend.docServicioHotels.show', compact('docServicioHotel'));
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
