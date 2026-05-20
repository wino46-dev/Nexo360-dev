<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocInfoHotelRequest;
use App\Http\Requests\StoreDocInfoHotelRequest;
use App\Http\Requests\UpdateDocInfoHotelRequest;
use App\Models\Ciudad;
use App\Models\DocInfoHotel;
use App\Models\Establecimiento;
use App\Models\Pai;
use App\Models\Provincium;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocInfoHotelController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_info_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocInfoHotel::with(['hotel', 'pais', 'provincia', 'ciudad'])->select(sprintf('%s.*', (new DocInfoHotel)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('hotel_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_info_hotel_show';
                $editGate      = 'doc_info_hotel_edit';
                $deleteGate    = 'doc_info_hotel_delete';
                $crudRoutePart = 'doc-info-hotels';

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

            $table->editColumn('categoria', function ($row) {
                return $row->categoria ? DocInfoHotel::CATEGORIA_SELECT[$row->categoria] : '';
            });
            $table->addColumn('pais_nombre', function ($row) {
                return $row->pais ? $row->pais->nombre : '';
            });

            $table->addColumn('provincia_nombre', function ($row) {
                return $row->provincia ? $row->provincia->nombre : '';
            });

            $table->addColumn('ciudad_nombre', function ($row) {
                return $row->ciudad ? $row->ciudad->nombre : '';
            });

            $table->editColumn('direccion', function ($row) {
                return $row->direccion ? $row->direccion : '';
            });
            $table->editColumn('codigo_postal', function ($row) {
                return $row->codigo_postal ? $row->codigo_postal : '';
            });
            $table->editColumn('latitud', function ($row) {
                return $row->latitud ? $row->latitud : '';
            });
            $table->editColumn('longitud', function ($row) {
                return $row->longitud ? $row->longitud : '';
            });
            $table->editColumn('telefono', function ($row) {
                return $row->telefono ? $row->telefono : '';
            });
            $table->editColumn('emergencias', function ($row) {
                return $row->emergencias ? $row->emergencias : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('web', function ($row) {
                return $row->web ? $row->web : '';
            });
            $table->editColumn('enlace_fotos', function ($row) {
                return $row->enlace_fotos ? $row->enlace_fotos : '';
            });
            $table->editColumn('cuenta_bancaria', function ($row) {
                return $row->cuenta_bancaria ? $row->cuenta_bancaria : '';
            });
            $table->editColumn('modos_cobro', function ($row) {
                return $row->modos_cobro ? $row->modos_cobro : '';
            });
            $table->editColumn('pet_friendly', function ($row) {
                return $row->pet_friendly ? DocInfoHotel::PET_FRIENDLY_SELECT[$row->pet_friendly] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'hotel', 'pais', 'provincia', 'ciudad']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();
        $pais             = Pai::get();
        $provincia        = Provincium::get();
        $ciudads          = Ciudad::get();

        return view('admin.docInfoHotels.index', compact('establecimientos', 'pais', 'provincia', 'ciudads'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_info_hotel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.docInfoHotels.create', compact('ciudads', 'hotels', 'pais', 'provincias'));
    }

    public function store(StoreDocInfoHotelRequest $request)
    {
        $docInfoHotel = DocInfoHotel::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docInfoHotel->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-info-hotels.index');
    }

    public function edit(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $hotels = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pais = Pai::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $provincias = Provincium::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ciudads = Ciudad::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docInfoHotel->load('hotel', 'pais', 'provincia', 'ciudad');

        return view('admin.docInfoHotels.edit', compact('ciudads', 'docInfoHotel', 'hotels', 'pais', 'provincias'));
    }

    public function update(UpdateDocInfoHotelRequest $request, DocInfoHotel $docInfoHotel)
    {
        $docInfoHotel->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-info-hotels.index');
    }

    public function show(DocInfoHotel $docInfoHotel)
    {
        abort_if(Gate::denies('doc_info_hotel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docInfoHotel->load('hotel', 'pais', 'provincia', 'ciudad');

        return view('admin.docInfoHotels.show', compact('docInfoHotel'));
    }

    public function destroy(DocInfoHotel $docInfoHotel)
    {
        // Deletion is not allowed per requirements
        abort(Response::HTTP_FORBIDDEN, 'Eliminar no permitido.');
    }

    public function massDestroy(MassDestroyDocInfoHotelRequest $request)
    {
        // Mass deletion is not allowed per requirements
        return abort(Response::HTTP_FORBIDDEN, 'Eliminar no permitido.');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_info_hotel_create') && Gate::denies('doc_info_hotel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocInfoHotel();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
