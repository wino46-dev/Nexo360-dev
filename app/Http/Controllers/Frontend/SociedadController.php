<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySociedadRequest;
use App\Http\Requests\StoreSociedadRequest;
use App\Http\Requests\UpdateSociedadRequest;
use App\Models\Sociedad;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SociedadController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('sociedad_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedads = Sociedad::with(['team', 'media'])->get();



        return view('frontend.sociedads.index', compact('sociedads'));
    }

    public function create()
    {
        abort_if(Gate::denies('sociedad_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.sociedads.create');
    }

    public function store(StoreSociedadRequest $request)
    {
        $sociedad = Sociedad::create($request->all());

        foreach ($request->input('imagenes', []) as $file) {
            $sociedad->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $sociedad->id]);
        }

        return redirect()->route('frontend.sociedads.index');
    }

    public function edit(Sociedad $sociedad)
    {
        abort_if(Gate::denies('sociedad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('frontend.sociedads.edit', compact('sociedad'));
    }

    public function update(UpdateSociedadRequest $request, Sociedad $sociedad)
    {
        $sociedad->update($request->all());

        if (count($sociedad->imagenes) > 0) {
            foreach ($sociedad->imagenes as $media) {
                if (! in_array($media->file_name, $request->input('imagenes', []))) {
                    $media->delete();
                }
            }
        }
        $media = $sociedad->imagenes->pluck('file_name')->toArray();
        foreach ($request->input('imagenes', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $sociedad->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('imagenes');
            }
        }

        return redirect()->route('frontend.sociedads.index');
    }

    public function show(Sociedad $sociedad)
    {
        abort_if(Gate::denies('sociedad_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedad->load('team', 'sociedadEstablecimientos');

        return view('frontend.sociedads.show', compact('sociedad'));
    }

    public function destroy(Sociedad $sociedad)
    {
        abort_if(Gate::denies('sociedad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sociedad->delete();

        return back();
    }

    public function massDestroy(MassDestroySociedadRequest $request)
    {
        $sociedads = Sociedad::find(request('ids'));

        foreach ($sociedads as $sociedad) {
            $sociedad->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('sociedad_create') && Gate::denies('sociedad_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Sociedad();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
