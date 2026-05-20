<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAyudaStepTotemRequest;
use App\Http\Requests\StoreAyudaStepTotemRequest;
use App\Http\Requests\UpdateAyudaStepTotemRequest;
use App\Models\AyudaStepTotem;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AyudaStepTotemController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ayuda_step_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AyudaStepTotem::with(['establecimiento'])->select(sprintf('%s.*', (new AyudaStepTotem)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ayuda_step_totem_show';
                $editGate      = 'ayuda_step_totem_edit';
                $deleteGate    = 'ayuda_step_totem_delete';
                $crudRoutePart = 'ayuda-step-totems';

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

            $table->rawColumns(['actions', 'placeholder', 'establecimiento']);

            return $table->make(true);
        }

        $establecimientos = Establecimiento::get();

        return view('external.ayudaStepTotems.index', compact('establecimientos'));
    }

    public function create()
    {
        abort_if(Gate::denies('ayuda_step_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.ayudaStepTotems.create', compact('establecimientos'));
    }

    public function store(StoreAyudaStepTotemRequest $request)
    {
        $ayudaStepTotem = AyudaStepTotem::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ayudaStepTotem->id]);
        }

        return redirect()->back();
    }

    public function edit(AyudaStepTotem $ayudaStepTotem)
    {
        abort_if(Gate::denies('ayuda_step_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::pluck('codigo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ayudaStepTotem->load('establecimiento');

        return view('external.ayudaStepTotems.edit', compact('ayudaStepTotem', 'establecimientos'));
    }

    public function update(UpdateAyudaStepTotemRequest $request, AyudaStepTotem $ayudaStepTotem)
    {
        $ayudaStepTotem->update($request->all());
        return redirect()->back();

    }

    public function show(AyudaStepTotem $ayudaStepTotem)
    {
        abort_if(Gate::denies('ayuda_step_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ayudaStepTotem->load('establecimiento');

        return view('external.ayudaStepTotems.show', compact('ayudaStepTotem'));
    }

    public function destroy(AyudaStepTotem $ayudaStepTotem)
    {
        abort_if(Gate::denies('ayuda_step_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ayudaStepTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyAyudaStepTotemRequest $request)
    {
        $ayudaStepTotems = AyudaStepTotem::find(request('ids'));

        foreach ($ayudaStepTotems as $ayudaStepTotem) {
            $ayudaStepTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ayuda_step_totem_create') && Gate::denies('ayuda_step_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AyudaStepTotem();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
