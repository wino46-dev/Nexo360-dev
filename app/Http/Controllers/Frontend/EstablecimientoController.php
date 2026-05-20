<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEstablecimientoRequest;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;
use App\Models\Establecimiento;
use App\Models\Sociedad;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class EstablecimientoController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('establecimiento_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::with(['sociedad', 'media'])->get();

        $sociedads = Sociedad::get();



        return view('frontend.establecimientos.index', compact('establecimientos', 'sociedads'));
    }

    public function create()
    {
        abort_if(Gate::denies('establecimiento_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedads = Sociedad::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.establecimientos.create', compact('sociedads'));
    }

    public function store(StoreEstablecimientoRequest $request)
    {
        $establecimiento = Establecimiento::create($request->all());

        foreach ($request->input('imagenes', []) as $file) {
            $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $establecimiento->id]);
        }

        return redirect()->route('frontend.establecimientos.index');
    }

    public function edit(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedads = Sociedad::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $establecimiento->load('sociedad');

        return view('frontend.establecimientos.edit', compact('establecimiento', 'sociedads'));
    }

    public function update(UpdateEstablecimientoRequest $request, Establecimiento $establecimiento)
    {
        $establecimiento->update($request->all());

        if (count($establecimiento->imagenes) > 0) {
            foreach ($establecimiento->imagenes as $media) {
                if (! in_array($media->file_name, $request->input('imagenes', []))) {
                    $media->delete();
                }
            }
        }
        $media = $establecimiento->imagenes->pluck('file_name')->toArray();
        foreach ($request->input('imagenes', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $establecimiento->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
            }
        }

        return redirect()->route('frontend.establecimientos.index');
    }

    public function show(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimiento->load('sociedad', 'establecimientoHabitacions', 'establecimientoTotems');

        return view('frontend.establecimientos.show', compact('establecimiento'));
    }

    public function destroy(Establecimiento $establecimiento)
    {
        abort_if(Gate::denies('establecimiento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimiento->delete();

        return back();
    }

    public function massDestroy(MassDestroyEstablecimientoRequest $request)
    {
        $establecimientos = Establecimiento::find(request('ids'));

        foreach ($establecimientos as $establecimiento) {
            $establecimiento->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('establecimiento_create') && Gate::denies('establecimiento_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Establecimiento();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
