<?php

namespace App\Http\Controllers\Frontend;

use App\Events\TotemResponseToBackoffice;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEventoHomeTotemRequest;
use App\Http\Requests\StoreEventoHomeTotemRequest;
use App\Http\Requests\UpdateEventoHomeTotemRequest;
use App\Models\EventoHomeTotem;
use App\Models\Establecimiento;
use App\Models\Team;
use App\Models\TipoEvento;
use App\Models\Reserva;
use App\Models\PagoTotem;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Relay\Event;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\Totem;

use App\Services\PagoService;

use App\Models\CheckIn;
use App\Models\Folio;
use App\Models\Reservation;

class EventoHomeTotemController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('evento_home_totem_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotems = EventoHomeTotem::with(['emisor', 'receptor', 'tipo_evento', 'media'])->get();

        $users = User::get();

        $tipo_eventos = TipoEvento::get();



        return view('frontend.eventoHomeTotems.index', compact('eventoHomeTotems', 'tipo_eventos', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tipo_eventos = TipoEvento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.eventoHomeTotems.create', compact('receptors', 'tipo_eventos'));
    }

    public function store(StoreEventoHomeTotemRequest $request)
    {
        $eventoHomeTotem = EventoHomeTotem::create($request->all());

        if ($request->input('respuesta_imagen', false)) {
            $eventoHomeTotem->addMedia(storage_path('tmp/uploads/' . basename($request->input('respuesta_imagen'))))->toMediaCollection('respuesta_imagen');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $eventoHomeTotem->id]);
        }

        return redirect()->route('frontend.evento-home-totems.index');
    }

    public function edit(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tipo_eventos = TipoEvento::pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');

        $eventoHomeTotem->load('emisor', 'receptor', 'tipo_evento');

        return view('frontend.eventoHomeTotems.edit', compact('eventoHomeTotem', 'tipo_eventos'));
    }

    public function update(UpdateEventoHomeTotemRequest $request, EventoHomeTotem $eventoHomeTotem)
    {
        try {

            $evento_actual = EventoHomeTotem::where('id', $request->id);
            $evento_actual->update([
                'respuesta_texto' => $request->respuesta_texto,
            ]);

            $act = $evento_actual->first();

            if (!empty($act->objeto)) {
                // actualizamos el checkin
                $checkIns = CheckIn::where('remote_id', $act->objeto)->first();
                if ($checkIns) {
                    $checkIns->accept_checkin = 1;
                    if (isset($request->accept_personal_data)) {
                        if (!empty($request->accept_personal_data)) {
                            $checkIns->accept_personal_data = 1;
                        } else {
                            $checkIns->accept_personal_data = 0;
                        }
                    }
                    $checkIns->save();
                }
            }
            if (isset($act->id)) {
                $respuesta_pusher = [
                    'evento_id' => $request->id,
                    'sesion_id' => $act->sesion_id,
                    'emisor_id' => $act->emisor_id,
                    'receptor_id' => $act->receptor->id,
                    'canal' => $act->canal_transmision,
                    'tipo_evento_id' => $act->tipo_evento_id,
                    'objeto' => $act->objeto,
                ];
                event(new TotemResponseToBackoffice($respuesta_pusher));

                return response()->json(['success' => "Imágen procesada Correctamente"]);
            } else {
                return response()->json(['error' => "Ha ocurrido un error al procesar la imágen"], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => "Ha ocurrido un error al procesar la imágen."], 500);
        }


        if ($request->input('respuesta_imagen', false)) {
            if (!$eventoHomeTotem->respuesta_imagen || $request->input('respuesta_imagen') !== $eventoHomeTotem->respuesta_imagen->file_name) {
                if ($eventoHomeTotem->respuesta_imagen) {
                    $eventoHomeTotem->respuesta_imagen->delete();
                }
                $eventoHomeTotem->addMedia(storage_path('tmp/uploads/' . basename($request->input('respuesta_imagen'))))->toMediaCollection('respuesta_imagen');
            }
        } elseif ($eventoHomeTotem->respuesta_imagen) {
            $eventoHomeTotem->respuesta_imagen->delete();
        }

        return redirect()->route('frontend.evento-home-totems.index');
    }

    public function show(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotem->load('emisor', 'receptor', 'tipo_evento');

        return view('frontend.eventoHomeTotems.show', compact('eventoHomeTotem'));
    }

    public function destroy(EventoHomeTotem $eventoHomeTotem)
    {
        abort_if(Gate::denies('evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $eventoHomeTotem->delete();

        return back();
    }

    public function massDestroy(MassDestroyEventoHomeTotemRequest $request)
    {
        $eventoHomeTotems = EventoHomeTotem::find(request('ids'));

        foreach ($eventoHomeTotems as $eventoHomeTotem) {
            $eventoHomeTotem->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('evento_home_totem_create') && Gate::denies('evento_home_totem_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new EventoHomeTotem();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function reservation(Request $request, $id)
    {
        $data = $request->all();
        $reservation = Reservation::where('id', $id)->first();

        $checkIns = CheckIn::where('reservation_id', $reservation->id)->get();
        $emailOptions = false;
        return view('frontend.eventoHomeTotems.reservation', compact('reservation', 'checkIns', 'emailOptions'));
    }

    public function parteViajeroHeader(Request $request, $id)
    {
        $data = $request->all();

        $checkin = CheckIn::where('remote_id', $id)
            ->where('pms', $data['pms'])->first();

        if (!$checkin) {
            Log::error('Checkin no encontrado ' . $id);
            return 'Checkin no encontrado';
        }
        $reservation_id = $checkin->reservation_id;

        $reservation = Reservation::where('id', $reservation_id)->first();
        $folio = Folio::where('id', $reservation->folio_id)->first();
        $establecimiento = Establecimiento::where('id', $folio->establecimiento_id)->first();
        $show_accept_personal_data = false;

        return view('admin.pdf.parte_header', compact('checkin', 'establecimiento', 'reservation', 'show_accept_personal_data'));
    }

    public function pagoPdf(Request $request)
    {
        $data = $request->all();

        $pago = PagoService::pdfGenerate($data['pago_origen_id']);

        return '<object data="' . url($pago) . '#toolbar=0" type="application/pdf" width="100%" height="700px">
            <param name="src" value="' . url($pago) . '">
            <param name="toolbar" value="0">    
            <param name="type" value="application/pdf">    
            <p>El navegador no puede mostrar este PDF. Puedes <a href="' . url($pago) . '">descargarlo aquí</a>.</p>
        </object>';
    }
}
