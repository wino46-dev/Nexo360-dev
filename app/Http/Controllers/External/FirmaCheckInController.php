<?php

namespace App\Http\Controllers\External;

use App\Events\SendTotemHomeEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyFirmaCheckInRequest;
use App\Http\Requests\StoreFirmaCheckInRequest;
use App\Http\Requests\UpdateFirmaCheckInRequest;
use App\Models\ControlSesion;
use App\Models\EventoHomeTotem;
use App\Models\FirmaCheckIn;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class FirmaCheckInController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('firma_check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = FirmaCheckIn::with(['sesion'])->select(sprintf('%s.*', (new FirmaCheckIn)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'firma_check_in_show';
                $editGate      = 'firma_check_in_edit';
                $deleteGate    = 'firma_check_in_delete';
                $crudRoutePart = 'firma-check-ins';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('sesion_id', function ($row) {
                return $row->sesion ? $row->sesion->id : '';
            });

            $table->editColumn('documento', function ($row) {
                return $row->documento ? '<a href="' . $row->documento->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'sesion', 'documento']);

            return $table->make(true);
        }

        $control_sesions = ControlSesion::get();
        $teams           = Team::get();

        return view('external.firmaCheckIns.index', compact('control_sesions', 'teams'));
    }

    public function create()
    {
        return back();
        abort_if(Gate::denies('firma_check_in_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('external.firmaCheckIns.create', compact('sesions'));
    }

    public function store(StoreFirmaCheckInRequest $request)
    {
        //$data = $request->all();
        //dd($data);
        //$firmaCheckIn = firmaCheckIn::create($request->all());

        $firmaCheckIn = firmaCheckIn::updateOrCreate([
            'sesion_id'      => $request->sesion_id,
            'objeto' => $request->objeto ?? ''
        ],[
            'firma_texto' => ''
        ]);


        if ($request->input('documento', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('documento'))))->toMediaCollection('documento');
        }

        if ($request->input('firma_imagen', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_imagen'))))->toMediaCollection('firma_imagen');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $firmaCheckIn->id]);
        }

        if(isset($request->sesion_id)){

            $sesion = ControlSesion::where('id',$request->sesion_id)->first();
            if(isset($sesion->id)){
                $event = [
                    'tipo_evento_id' => 4,
                    'id'             => $firmaCheckIn->id,
                    'sesion_id'      => $request->sesion_id,
                    'receptor_id'    => $sesion->receptor_id,
                    'emisor_id'    => $sesion->emisor_id,
                    'canal_transmision' => 'Home Inferior',
                    'mensaje' => $request->mensaje
                ];
                $eventoHomeTotem = EventoHomeTotem::create($event);

                if(isset($eventoHomeTotem->id)){
                    event(new SendTotemHomeEvent($event));
                }

            }
        }

        return response()->json(['success'=>"Solicitud de firma lanzada correctamente"]);


        return redirect()->route('external.firma-check-ins.index');
    }

    public function edit(FirmaCheckIn $firmaCheckIn)
    {
        return back();
        abort_if(Gate::denies('firma_check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->load('sesion');

        return view('external.firmaCheckIns.edit', compact('firmaCheckIn'));
    }

    public function update(UpdateFirmaCheckInRequest $request, FirmaCheckIn $firmaCheckIn)
    {
        $firmaCheckIn->update($request->all());

        if ($request->input('documento', false)) {
            if (! $firmaCheckIn->documento || $request->input('documento') !== $firmaCheckIn->documento->file_name) {
                if ($firmaCheckIn->documento) {
                    $firmaCheckIn->documento->delete();
                }
                $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('documento'))))->toMediaCollection('documento');
            }
        } elseif ($firmaCheckIn->documento) {
            $firmaCheckIn->documento->delete();
        }

        if ($request->input('firma_imagen', false)) {
            if (! $firmaCheckIn->firma_imagen || $request->input('firma_imagen') !== $firmaCheckIn->firma_imagen->file_name) {
                if ($firmaCheckIn->firma_imagen) {
                    $firmaCheckIn->firma_imagen->delete();
                }
                $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_imagen'))))->toMediaCollection('firma_imagen');
            }
        } elseif ($firmaCheckIn->firma_imagen) {
            $firmaCheckIn->firma_imagen->delete();
        }

        return redirect()->route('external.firma-check-ins.index');
    }

    public function show(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->load('sesion');

        return view('external.firmaCheckIns.show', compact('firmaCheckIn'));
    }

    public function destroy(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->delete();

        return back();
    }

    public function massDestroy(MassDestroyFirmaCheckInRequest $request)
    {
        $firmaCheckIns = FirmaCheckIn::find(request('ids'));

        foreach ($firmaCheckIns as $firmaCheckIn) {
            $firmaCheckIn->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('firma_check_in_create') && Gate::denies('firma_check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new FirmaCheckIn();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
