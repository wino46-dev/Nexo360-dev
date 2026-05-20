<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLayoutHomeRequest;
use App\Http\Requests\StoreLayoutHomeRequest;
use App\Http\Requests\UpdateLayoutHomeRequest;
use App\Models\LayoutHome;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LayoutHomeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('layout_home_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LayoutHome::select(sprintf('%s.*', (new LayoutHome)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'layout_home_show';
                $editGate      = 'layout_home_edit';
                $deleteGate    = 'layout_home_delete';
                $crudRoutePart = 'layout-homes';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('tipo', function ($row) {
                return $row->tipo ? LayoutHome::TIPO_SELECT[$row->tipo] : '';
            });
            $table->editColumn('imagen_1', function ($row) {
                if ($photo = $row->imagen_1) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('imagen_2', function ($row) {
                if ($photo = $row->imagen_2) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });

            $table->rawColumns(['actions', 'placeholder', 'imagen_1', 'imagen_2']);

            return $table->make(true);
        }

        $teams = Team::get();

        return view('external.layoutHomes.index', compact('teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('layout_home_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.layoutHomes.create');
    }

    public function store(StoreLayoutHomeRequest $request)
    {
        $layoutHome = LayoutHome::create($request->all());

        if ($request->input('imagen_1', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_1'))))->toMediaCollection('imagen_1');
        }

        if ($request->input('imagen_2', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_2'))))->toMediaCollection('imagen_2');
        }

        if ($request->input('imagen_3', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_3'))))->toMediaCollection('imagen_3');
        }

        if ($request->input('imagen_4', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_4'))))->toMediaCollection('imagen_4');
        }

        if ($request->input('imagen_5', false)) {
            $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_5'))))->toMediaCollection('imagen_5');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $layoutHome->id]);
        }

        return redirect()->route('external.layout-homes.index');
    }

    public function edit(LayoutHome $layoutHome)
    {
        abort_if(Gate::denies('layout_home_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('external.layoutHomes.edit', compact('layoutHome'));
    }

    public function update(UpdateLayoutHomeRequest $request, LayoutHome $layoutHome)
    {
        $layoutHome->update($request->all());

        if ($request->input('imagen_1', false)) {
            if (! $layoutHome->imagen_1 || $request->input('imagen_1') !== $layoutHome->imagen_1->file_name) {
                if ($layoutHome->imagen_1) {
                    $layoutHome->imagen_1->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_1'))))->toMediaCollection('imagen_1');
            }
        } elseif ($layoutHome->imagen_1) {
            $layoutHome->imagen_1->delete();
        }

        if ($request->input('imagen_2', false)) {
            if (! $layoutHome->imagen_2 || $request->input('imagen_2') !== $layoutHome->imagen_2->file_name) {
                if ($layoutHome->imagen_2) {
                    $layoutHome->imagen_2->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_2'))))->toMediaCollection('imagen_2');
            }
        } elseif ($layoutHome->imagen_2) {
            $layoutHome->imagen_2->delete();
        }

        if ($request->input('imagen_3', false)) {
            if (! $layoutHome->imagen_3 || $request->input('imagen_3') !== $layoutHome->imagen_3->file_name) {
                if ($layoutHome->imagen_3) {
                    $layoutHome->imagen_3->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_3'))))->toMediaCollection('imagen_3');
            }
        } elseif ($layoutHome->imagen_3) {
            $layoutHome->imagen_3->delete();
        }

        if ($request->input('imagen_4', false)) {
            if (! $layoutHome->imagen_4 || $request->input('imagen_4') !== $layoutHome->imagen_4->file_name) {
                if ($layoutHome->imagen_4) {
                    $layoutHome->imagen_4->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_4'))))->toMediaCollection('imagen_4');
            }
        } elseif ($layoutHome->imagen_4) {
            $layoutHome->imagen_4->delete();
        }

        if ($request->input('imagen_5', false)) {
            if (! $layoutHome->imagen_5 || $request->input('imagen_5') !== $layoutHome->imagen_5->file_name) {
                if ($layoutHome->imagen_5) {
                    $layoutHome->imagen_5->delete();
                }
                $layoutHome->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_5'))))->toMediaCollection('imagen_5');
            }
        } elseif ($layoutHome->imagen_5) {
            $layoutHome->imagen_5->delete();
        }

        return redirect()->route('external.layout-homes.index');
    }

    public function show(LayoutHome $layoutHome)
    {
        abort_if(Gate::denies('layout_home_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('external.layoutHomes.show', compact('layoutHome'));
    }

    public function destroy(LayoutHome $layoutHome)
    {
        abort_if(Gate::denies('layout_home_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $layoutHome->delete();

        return back();
    }

    public function massDestroy(MassDestroyLayoutHomeRequest $request)
    {
        $layoutHomes = LayoutHome::find(request('ids'));

        foreach ($layoutHomes as $layoutHome) {
            $layoutHome->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('layout_home_create') && Gate::denies('layout_home_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new LayoutHome();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
