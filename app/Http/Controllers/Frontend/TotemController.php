<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTotemRequest;
use App\Http\Requests\StoreTotemRequest;
use App\Http\Requests\UpdateTotemRequest;
use App\Models\Establecimiento;
use App\Models\Team;
use App\Models\Totem;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class TotemController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totems = Totem::with(['establecimiento', 'media'])->get();

        $establecimientos = Establecimiento::get();



        return view('frontend.totems.index', compact('establecimientos', 'totems'));
    }

    public function create()
    {
        abort_if(Gate::denies('totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.totems.create', compact('establecimientos'));
    }

    public function store(StoreTotemRequest $request)
    {
        $totem = Totem::create($request->all());

        foreach ($request->input('imagenes', []) as $file) {
            $totem->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $totem->id]);
        }

        return redirect()->route('frontend.totems.index');
    }

    public function edit(Totem $totem)
    {
        abort_if(Gate::denies('totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $totem->load('establecimiento');

        return view('frontend.totems.edit', compact('establecimientos', 'totem'));
    }

    public function update(UpdateTotemRequest $request, Totem $totem)
    {
        $totem->update($request->all());

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

        return redirect()->route('frontend.totems.index');
    }

    public function show(Totem $totem)
    {
        abort_if(Gate::denies('totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $totem->load('establecimiento', 'totemUsers');

        return view('frontend.totems.show', compact('totem'));
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
}
