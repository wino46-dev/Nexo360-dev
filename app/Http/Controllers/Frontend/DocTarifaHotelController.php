<?php

namespace App\Http\Controllers\Frontend;

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

class DocTarifaHotelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('doc_tarifa_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docTarifaHotels = DocTarifaHotel::with(['establecimiento', 'habitacion'])->get();

        $establecimientos = Establecimiento::get();

        $doc_habitacion_hotels = DocHabitacionHotel::get();

        return view('frontend.docTarifaHotels.index', compact('docTarifaHotels', 'doc_habitacion_hotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_tarifa_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = DocHabitacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docTarifaHotels.create', compact('establecimientos', 'habitacions'));
    }

    public function store(StoreDocTarifaHotelRequest $request)
    {
        $docTarifaHotel = DocTarifaHotel::create($request->all());

        return redirect()->route('frontend.doc-tarifa-hotels.index');
    }

    public function edit(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $habitacions = DocHabitacionHotel::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docTarifaHotel->load('establecimiento', 'habitacion');

        return view('frontend.docTarifaHotels.edit', compact('docTarifaHotel', 'establecimientos', 'habitacions'));
    }

    public function update(UpdateDocTarifaHotelRequest $request, DocTarifaHotel $docTarifaHotel)
    {
        $docTarifaHotel->update($request->all());

        return redirect()->route('frontend.doc-tarifa-hotels.index');
    }

    public function show(DocTarifaHotel $docTarifaHotel)
    {
        abort_if(Gate::denies('doc_tarifa_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docTarifaHotel->load('establecimiento', 'habitacion');

        return view('frontend.docTarifaHotels.show', compact('docTarifaHotel'));
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
