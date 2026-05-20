<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocNoDeseadoHotelRequest;
use App\Http\Requests\StoreDocNoDeseadoHotelRequest;
use App\Http\Requests\UpdateDocNoDeseadoHotelRequest;
use App\Models\DocNoDeseadoHotel;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocNoDeseadoHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocNoDeseadoHotel::with(['establecimiento'])->select(sprintf('%s.*', (new DocNoDeseadoHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_no_deseado_hotel_show';
                $editGate      = 'doc_no_deseado_hotel_edit';
                $deleteGate    = 'doc_no_deseado_hotel_delete';
                $crudRoutePart = 'doc-no-deseado-hotels';

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

            $table->editColumn('no_deseado', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->no_deseado ? 'checked' : null) . '>';
            });
            $table->editColumn('motivo', function ($row) {
                return $row->motivo ? $row->motivo : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'no_deseado']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('external.docNoDeseadoHotels.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.docNoDeseadoHotels.create', compact('establecimientos'));
    }

    public function store(StoreDocNoDeseadoHotelRequest $request)
    {
        $docNoDeseadoHotel = DocNoDeseadoHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docNoDeseadoHotel->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-no-deseado-hotels.index');
    }

    public function edit(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docNoDeseadoHotel->load('establecimiento');

        return view('external.docNoDeseadoHotels.edit', compact('docNoDeseadoHotel', 'establecimientos'));
    }

    public function update(UpdateDocNoDeseadoHotelRequest $request, DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        $docNoDeseadoHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.doc-no-deseado-hotels.index');
    }

    public function show(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotel->load('establecimiento');

        return view('external.docNoDeseadoHotels.show', compact('docNoDeseadoHotel'));
    }

    public function destroy(DocNoDeseadoHotel $docNoDeseadoHotel)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docNoDeseadoHotel->delete();

        return back();
    }

    public function massDestroy(MassDestroyDocNoDeseadoHotelRequest $request)
    {
        $docNoDeseadoHotels = DocNoDeseadoHotel::find(request('ids'));

        foreach ($docNoDeseadoHotels as $docNoDeseadoHotel) {
            $docNoDeseadoHotel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_create') && Gate::denies('doc_no_deseado_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocNoDeseadoHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
