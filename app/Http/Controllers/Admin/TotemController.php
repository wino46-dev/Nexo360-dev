<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTotemRequest;
use App\Http\Requests\StoreTotemRequest;
use App\Http\Requests\UpdateTotemRequest;
use App\Models\ConfiguracionGrabador;
use App\Models\ConfiguracionTpv;
use App\Models\ConfiguracionVideo;
use App\Models\Establecimiento;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TotemController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            //sleep(60);
            $query = Totem::with(['establecimiento'])->select(sprintf('%s.*', (new Totem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'totem_show';
                $editGate      = 'totem_edit';
                $deleteGate    = 'totem_delete';
                $crudRoutePart = 'totems';

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

            $table->editColumn('codigo', function ($row) {
                return $row->codigo ? $row->codigo : '';
            });
            $table->editColumn('fuente_imagenes', function ($row) {
                return $row->fuente_imagenes ? Totem::FUENTE_IMAGENES_SELECT[$row->fuente_imagenes] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'establecimiento']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();
        $teams            = Team::get();

        return view('admin.totems.index', compact('establecimientos', 'teams'));
    }

    public function create()
    {
        abort_if(Gate::denies('totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.totems.create', compact('establecimientos'));
    }

    public function store(StoreTotemRequest $request)
    {
        $totem = Totem::create($request->all());

        foreach ($request->input('imagenes_pagina_inicial', []) as $file) {
            $totem->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes_pagina_inicial');
        }

        foreach ($request->input('imagenes', []) as $file) {
            $totem->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        if ($request->input('spinner', false)) {
            $totem->addMedia(storage_path('tmp/uploads/' . basename($request->input('spinner'))))->toMediaCollection('spinner');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $totem->id]);
        }

        return redirect()->route('admin.totems.index');
    }

    public function edit(Totem $totem)
    {
        abort_if(Gate::denies('totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totem->load('establecimiento');

        return view('admin.totems.edit', compact('establecimientos', 'totem'));
    }

    public function update(UpdateTotemRequest $request, Totem $totem)
    {
        $totem->update($request->all());

        if (count($totem->imagenes_pagina_inicial) > 0) {
            foreach ($totem->imagenes_pagina_inicial as $media) {
                if (! in_array($media->file_name, $request->input('imagenes_pagina_inicial', []))) {
                    $media->delete();
                }
            }
        }
        $media = $totem->imagenes_pagina_inicial->pluck('file_name')->toArray();
        foreach ($request->input('imagenes_pagina_inicial', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $totem->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes_pagina_inicial');
            }
        }

        if (count($totem->imagenes) > 0) {
            foreach ($totem->imagenes as $media) {
                if (! in_array($media->file_name, $request->input('imagenes', []))) {
                    $media->delete();
                }
            }
        }
        $media = $totem->imagenes->pluck('file_name')->toArray();
        foreach ($request->input('imagenes', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $totem->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
            }
        }

        if ($request->input('spinner', false)) {
            if (! $totem->spinner || $request->input('spinner') !== $totem->spinner->file_name) {
                if ($totem->spinner) {
                    $totem->spinner->delete();
                }
                $totem->addMedia(storage_path('tmp/uploads/' . basename($request->input('spinner'))))->toMediaCollection('spinner');
            }
        } elseif ($totem->spinner) {
            $totem->spinner->delete();
        }

        if ($request->input('imagen_pagina_2', false)) {
            if (! $totem->imagen_pagina_2 || $request->input('imagen_pagina_2') !== $totem->imagen_pagina_2->file_name) {
                if ($totem->imagen_pagina_2) {
                    $totem->imagen_pagina_2->delete();
                }
                $totem->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_pagina_2'))))->toMediaCollection('imagen_pagina_2');
            }
        } elseif ($totem->imagen_pagina_2) {
            $totem->imagen_pagina_2->delete();
        }

        if ($request->input('imagen_pagina_llamada', false)) {
            if (! $totem->imagen_pagina_llamada || $request->input('imagen_pagina_llamada') !== $totem->imagen_pagina_llamada->file_name) {
                if ($totem->imagen_pagina_llamada) {
                    $totem->imagen_pagina_llamada->delete();
                }
                $totem->addMedia(storage_path('tmp/uploads/' . basename($request->input('imagen_pagina_llamada'))))->toMediaCollection('imagen_pagina_llamada');
            }
        } elseif ($totem->imagen_pagina_llamada) {
            $totem->imagen_pagina_llamada->delete();
        }

        return redirect()->route('admin.totems.index');
    }

    public function show(Totem $totem)
    {
        abort_if(Gate::denies('totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totem->load('establecimiento', 'totemUsers', 'totemConfiguracionTpvs');

        $configuracionTpv = ConfiguracionTpv::where('totem_id',$totem->id)->first();

        $configuracionVideo = ConfiguracionVideo::where('totem_id',$totem->id)->first();

        $configuracionGrabador = ConfiguracionGrabador::where('totem_id',$totem->id)->first();

        //return $configuracionTpv;

        return view('admin.totems.show', compact(['totem','configuracionTpv','configuracionVideo','configuracionGrabador']));
    }

    public function destroy(Totem $totem)
    {
        abort_if(Gate::denies('totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totem->delete();

        return back();
    }

    public function massDestroy(MassDestroyTotemRequest $request)
    {
        $totems = Totem::find(request('ids'));

        foreach ($totems as $totem) {
            $totem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('totem_create') && Gate::denies('totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Totem();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function updateMass(Request $request)
    {        
        
        $data = $request->all();
        foreach($data['ids'] as $id){
            $totem = Totem::find($id);

            if($totem){
                $totem->update([
                    'fuente_imagenes_pagina_1' => $data['fuente_imagenes_pagina_1'],
                ]);
            }
        }        

        return response()->json(['success' => true]);
    }
}
