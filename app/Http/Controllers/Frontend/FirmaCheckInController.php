<?php

namespace App\Http\Controllers\Frontend;

use App\Events\TotemResponseToBackoffice;
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

class FirmaCheckInController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('firma_check_in_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIns = FirmaCheckIn::with(['sesion', 'media'])->get();

        $control_sesions = ControlSesion::get();



        return view('frontend.firmaCheckIns.index', compact('control_sesions', 'firmaCheckIns'));
    }

    public function create()
    {
        abort_if(Gate::denies('firma_check_in_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sesions = ControlSesion::pluck('estado_sesion', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.firmaCheckIns.create', compact('sesions'));
    }

    public function signaturePad(Request $request){
        try {
            $evento_actual = FirmaCheckIn::where('id',$request->id_evento_sign)->first();
            $evento_actual->update([
                'firma_texto' => $request->sign,
            ]);

            if(isset($evento_actual->id)){
                $controlSesion = ControlSesion::where('id',$evento_actual->sesion_id)->first();
            }
            if(isset($controlSesion->id)){
                $respuesta_pusher = [
                    'sesion_id' => $evento_actual->sesion_id,
                    'emisor_id' => $controlSesion->receptor_id,
                    'receptor_id' => $controlSesion->emisor_id,
                    'canal' => 'Home Inferior',
                    'tipo_evento_id' => 10,
                ];
                event(new TotemResponseToBackoffice($respuesta_pusher));

                return response()->json(['success'=>"Firma procesada Correctamente"]);
            }else{
                return response()->json(['error'=>"Ha ocurrido un error al procesar la imágen"]);
            }
        }catch (\Exception $e){
            return response()->json(['success'=>'Mensaje:'.$e->getMessage()]);
        }
    }

    public function store(StoreFirmaCheckInRequest $request)
    {
        $firmaCheckIn = FirmaCheckIn::create($request->all());

        if ($request->input('documento', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('documento'))))->toMediaCollection('documento');
        }

        if ($request->input('firma_imagen', false)) {
            $firmaCheckIn->addMedia(storage_path('tmp/uploads/' . basename($request->input('firma_imagen'))))->toMediaCollection('firma_imagen');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $firmaCheckIn->id]);
        }

        return redirect()->route('frontend.firma-check-ins.index');
    }

    public function edit(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->load('sesion');

        return view('frontend.firmaCheckIns.edit', compact('firmaCheckIn'));
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

        return redirect()->route('frontend.firma-check-ins.index');
    }

    public function show(FirmaCheckIn $firmaCheckIn)
    {
        abort_if(Gate::denies('firma_check_in_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $firmaCheckIn->load('sesion');

        return view('frontend.firmaCheckIns.show', compact('firmaCheckIn'));
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

    public function signaturePadLoad(Request $request){
        //return 'ok';
        return '<x-creagia-signature-pad
                                                    
                border-color="#eaeaea"
                pad-classes="rounded-xl border-2"
                button-classes="bg-gray-100 px-4 py-2 rounded-xl mt-4"
                clear-name="Borrar firma"
                submit-name="Enviar"
                :disabled-without-signature="true"

        />
        <script src="vendor/sign-pad/sign-pad.min.js"></script>
        ';
    }
}
