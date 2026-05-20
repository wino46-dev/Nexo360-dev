<?php

namespace App\Http\Controllers\Frontend;

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

class DocMetodoPagoHotelController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docMetodoPagoHotels = DocMetodoPagoHotel::with(['establecimiento'])->get();

        $establecimientos = Establecimiento::get();

        return view('frontend.docMetodoPagoHotels.index', compact('docMetodoPagoHotels', 'establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.docMetodoPagoHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocMetodoPagoHotelRequest $request)
    {
        $docMetodoPagoHotel = DocMetodoPagoHotel::create($request->all());

        return redirect()->route('frontend.doc-metodo-pago-hotels.index');
    }

    public function edit(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docMetodoPagoHotel->load('establecimiento');

        return view('frontend.docMetodoPagoHotels.edit', compact('docMetodoPagoHotel', 'establecimientos'));
    }

    public function update(UpdateDocMetodoPagoHotelRequest $request, DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        $docMetodoPagoHotel->update($request->all());

        return redirect()->route('frontend.doc-metodo-pago-hotels.index');
    }

    public function show(DocMetodoPagoHotel $docMetodoPagoHotel)
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docMetodoPagoHotel->load('establecimiento');

        return view('frontend.docMetodoPagoHotels.show', compact('docMetodoPagoHotel'));
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
