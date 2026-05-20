<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDocHotelReceptionInfoRequest;
use App\Http\Requests\StoreDocHotelReceptionInfoRequest;
use App\Http\Requests\UpdateDocHotelReceptionInfoRequest;
use App\Models\DocHotelReceptionInfo;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DocHotelReceptionInfoController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DocHotelReceptionInfo::with(['establecimiento'])->select(sprintf('%s.*', (new DocHotelReceptionInfo)->table));
            if ($request->filled('establecimiento_id')) {
                $query->where('establecimiento_id', $request->get('establecimiento_id'));
            }
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'doc_hotel_reception_info_show';
                $editGate      = 'doc_hotel_reception_info_edit';
                $deleteGate    = 'doc_hotel_reception_info_delete';
                $crudRoutePart = 'doc-hotel-reception-infos';

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

            $table->editColumn('hour_open', function ($row) {
                return $row->hour_open ? $row->hour_open : '';
            });
            $table->editColumn('hour_close', function ($row) {
                return $row->hour_close ? $row->hour_close : '';
            });
            $table->editColumn('open_holiday', function ($row) {
                return $row->open_holiday ? $row->open_holiday : '';
            });
            $table->editColumn('close_holiday', function ($row) {
                return $row->close_holiday ? $row->close_holiday : '';
            });
            $table->editColumn('acces_type_after_hour', function ($row) {
                return $row->acces_type_after_hour ? DocHotelReceptionInfo::ACCES_TYPE_AFTER_HOUR_SELECT[$row->acces_type_after_hour] : '';
            });
            $table->editColumn('box_photo', function ($row) {
                if (! $row->box_photo) {
                    return '';
                }
                $links = [];
                foreach ($row->box_photo as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento', 'box_photo']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('admin.docHotelReceptionInfos.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('doc_hotel_reception_info_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.docHotelReceptionInfos.create', compact('establecimientos'));
    }

    public function store(StoreDocHotelReceptionInfoRequest $request)
    {
        $docHotelReceptionInfo = DocHotelReceptionInfo::create($request->all());

        foreach ($request->input('box_photo', []) as $file) {
            $docHotelReceptionInfo->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('box_photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $docHotelReceptionInfo->id]);
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-hotel-reception-infos.index');
    }

    public function edit(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $docHotelReceptionInfo->load('establecimiento');

        return view('admin.docHotelReceptionInfos.edit', compact('docHotelReceptionInfo', 'establecimientos'));
    }

    public function update(UpdateDocHotelReceptionInfoRequest $request, DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        $docHotelReceptionInfo->update($request->all());

        if (count($docHotelReceptionInfo->box_photo) > 0) {
            foreach ($docHotelReceptionInfo->box_photo as $media) {
                if (! in_array($media->file_name, $request->input('box_photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $docHotelReceptionInfo->box_photo->pluck('file_name')->toArray();
        foreach ($request->input('box_photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $docHotelReceptionInfo->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('box_photo');
            }
        }

        $prefix = request()->routeIs('external.*') ? 'external' : 'admin';
        return redirect()->route($prefix . '.doc-hotel-reception-infos.index');
    }

    public function show(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $docHotelReceptionInfo->load('establecimiento');

        return view('admin.docHotelReceptionInfos.show', compact('docHotelReceptionInfo'));
    }

    public function destroy(DocHotelReceptionInfo $docHotelReceptionInfo)
    {
        // Deletion is not allowed per requirements
        abort(Response::HTTP_FORBIDDEN, 'Eliminar no permitido.');
    }

    public function massDestroy(MassDestroyDocHotelReceptionInfoRequest $request)
    {
        // Mass deletion is not allowed per requirements
        return abort(Response::HTTP_FORBIDDEN, 'Eliminar no permitido.');
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('doc_hotel_reception_info_create') && Gate::denies('doc_hotel_reception_info_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DocHotelReceptionInfo();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
