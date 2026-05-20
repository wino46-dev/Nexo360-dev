<?php

namespace App\Http\Controllers\External;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Events\NotifyPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyControlSesionRequest;
use App\Http\Requests\StoreControlSesionRequest;
use App\Http\Requests\UpdateControlSesionRequest;
use App\Models\AyudaStepTotem;
use App\Models\ControlSesion;
use App\Models\Establecimiento;
use App\Models\EventoHomeTotem;
use App\Models\GrabacionTarjetum;
use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use App\Models\Team;
use App\Models\TipoEvento;
use App\Models\Totem;
use App\Models\Sociedad;
use App\Models\User;
use App\Models\CheckIn;
use App\Models\ZonaComun;
use App\Models\DocIncidenciaHotel;
use Cassandra\Collection;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Events\SendTotemHomeEvent;
use DB;

use App\Events\TotemReservationLoadEvent;
use App\Models\Reserva;
use Mail;
use App\Mail\ParteViajeroMail;
use App\Models\Reservation;
use App\Services\HotelApiService;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\Jobs\ExportPartesPdf;




class CallManagerController extends Controller
{

    public function create()
    {
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = auth()->user()->id;
        $current_user_sesion = ControlSesion::where('estado_sesion', 1)->where('emisor_id', $emisors)->count();
        if ($current_user_sesion >= 1) {
            return back()->with(['message' => 'No puedes iniciar otra sesión remota, hasta que cierres la actual']);
        }
        $active_sesions = ControlSesion::where('estado_sesion', 1)->with(['emisor'])->get();
        $id_sesions = [];

        foreach ($active_sesions as $sesion_obj) {
            array_push($id_sesions, $sesion_obj->receptor_id);
        }


        $receptors = User::where('id', '!=', auth()->user()->id)
            ->where('totem_id', '!=', null)
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');


        return view('external.controlSesions.create', compact('emisors', 'receptors', 'id_sesions', 'active_sesions'));
    }

    public function store(StoreControlSesionRequest $request)
    {
        $controlSesion = ControlSesion::create($request->all());
        $usuarioTotem = User::where('id', $controlSesion->receptor_id)->with(['totem'])->first();
        $valor_string = '';
        if (isset($usuarioTotem->totem->id)) {
            $establecimiento = Establecimiento::where('id', $usuarioTotem->totem->establecimiento_id)->first();
            if (isset($establecimiento->id)) {
                $valor_string .= 'Establecimiento: ' . $establecimiento->nombre;
                $valor_string .= ' - Totem: ' . $usuarioTotem->totem->codigo;


                return back()->with(['success' => 'notify_conection']);
            }
        }
        return back();
    }

    public function index(Request $request)
    {

        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(Gate::denies('evento_home_totem_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $tipo_eventos = TipoEvento::where('id', '!=', 1)->where('id', '!=', 2)->pluck('nombre', 'id')->prepend(trans('global.pleaseSelect'), '');
        $control_actual = ControlSesion::where('emisor_id', auth()->user()->id)->where('estado_sesion', 1)->first();

        if (isset($control_actual->id)) {

            $eventos_sesion_push = EventoHomeTotem::select('evento_home_totems.*', 'tipo_eventos.*', 'users.name')
                ->join('tipo_eventos', 'tipo_eventos.id', '=', 'evento_home_totems.tipo_evento_id')
                ->join('users', 'users.id', '=', 'evento_home_totems.emisor_id')
                ->where('evento_home_totems.sesion_id', $control_actual->id)
                ->orderBy('evento_home_totems.id', 'DESC')
                ->get();

            $documento_anverso = EventoHomeTotem::select('evento_home_totems.*', 'tipo_eventos.*', 'users.name')
                ->join('tipo_eventos', 'tipo_eventos.id', '=', 'evento_home_totems.tipo_evento_id')
                ->join('users', 'users.id', '=', 'evento_home_totems.emisor_id')
                ->where('evento_home_totems.sesion_id', $control_actual->id)
                ->where('evento_home_totems.tipo_evento_id', 3)
                ->orderBy('evento_home_totems.id', 'DESC')
                ->first();

            $documento_reverso = EventoHomeTotem::select('evento_home_totems.*', 'tipo_eventos.*', 'users.name')
                ->join('tipo_eventos', 'tipo_eventos.id', '=', 'evento_home_totems.tipo_evento_id')
                ->join('users', 'users.id', '=', 'evento_home_totems.emisor_id')
                ->where('evento_home_totems.tipo_evento_id', 6)
                ->where('evento_home_totems.sesion_id', $control_actual->id)
                ->orderBy('evento_home_totems.id', 'DESC')
                ->first();

            $pagos_sesion = PagoTotem::with(['pagoOrigenRespuestaPagos', 'emisor'])->where('sesion_id', $control_actual->id)->get();
            $tarjetas_sesion = GrabacionTarjetum::with(['emisor'])->where('sesion_id', $control_actual->id)->get();

            $establecimiento = "";
            $ayudaStepTotem = "";
            $receptor = $control_actual->receptor_id;
            $totem_base = User::where('id', $receptor)->first();
            $imagenes = [];
            $imagenes_tour = [];
            if (isset($totem_base->id)) {
                $totem = Totem::where('id', $totem_base->totem_id)->first();

                if (isset($totem->id)) {
                    $establecimiento = Establecimiento::where('id', $totem->establecimiento_id)->first();

                    $imagenes_tour = $establecimiento->tour_images;

                    if ($totem->fuente_imagenes == 'Totem') {
                        $imagenes = $totem->imagenes;
                    } else if ($totem->fuente_imagenes == 'Establecimiento') {

                        if (isset($establecimiento->id)) {
                            $imagenes = $establecimiento->imagenes;
                        }
                    } else if ($totem->fuente_imagenes == 'Sociedad') {
                        if (isset($establecimiento->id)) {
                            $sociedad = Sociedad::where('id', $establecimiento->sociedad_id)->first();
                            if (isset($sociedad->id)) {
                                $imagenes = $sociedad->imagenes;
                            }
                        }
                    }

                    if (isset($establecimiento->id)) {
                        $var_establecimiento = $establecimiento;
                        $ayudaStepTotem = AyudaStepTotem::where('establecimiento_id', $establecimiento->id)->first();
                    }
                }
            }

            $control_sesion_actual = EventoHomeTotem::where('sesion_id', $control_actual->id)->with(['tipo_evento'])->get();


            $zonas_comunes = ZonaComun::where('estado', 1)->where('establecimiento_id', $establecimiento->id  ?? '')
                ->orderBy('orden', 'asc')->get();

            // Incidencias abiertas del establecimiento (Abierta o En Revisión)
            $incidencias_abiertas = collect();
            if (isset($establecimiento->id)) {
                $incidencias_abiertas = DocIncidenciaHotel::where('establecimiento_id', $establecimiento->id)
                    ->whereIn('estado', ['Abierta', 'En Revisión'])
                    ->orderBy('fecha', 'desc')
                    ->get();
            }

            $error_api = [];

            if (empty($establecimiento->api_pms)) {
                $establecimiento->api_pms = 'local';
            }

            if ($establecimiento->api_pms == 'roomdoo') {
                if (
                    empty($establecimiento->api_pms)
                    || empty($establecimiento->api_pms_url)
                    || empty($establecimiento->api_pms_username)
                    || empty($establecimiento->api_pms_password)
                    || empty($establecimiento->remote_hotel_id)
                ) {
                    $error_api[] = 'Este establecimiento no tiene configurado la Api del PMS';
                    $control_actual = null;
                }
                if (empty($establecimiento->pms_payment_method_totem)) {
                    //$error_api[] = 'No esta configurado el método de pago para el Totem';
                    //$control_actual = null;
                }
                if (empty($establecimiento->pms_payment_method_manual)) {
                    //$error_api[] = 'No esta configurado el método de pago Manual';
                    //$control_actual = null;
                }

                $user_current = User::where('id', auth()->user()->id)->first();

                if (empty($user_current->login_pms_establecimiento)) {
                    if (empty($user_current->pms_password)) {
                        $error_api[] = 'El Usuario No tiene configurado el Password del PMS';
                        $control_actual = null;
                    }
                }

                //session(['api_pms' => $establecimiento->api_pms]);
                session(['api_establecimiento_id' => $establecimiento->id]);

                try {
                    $hotel_api = new HotelApiService($establecimiento->id);
                    // Reset auth cache for the PMS before attempting login
                    $hotel_api->resetAuthCache();
                    $login_check = $hotel_api->login();
                    $pms_active = true;
                } catch (\Exception $e) {
                    $error_api[] = 'Usuario y/o Password del PMS incorrecto';
                    $pms_active = false;
                }
            } else {
                $pms_active = true;
                $hotel_api = new HotelApiService($establecimiento->id);
                session(['api_establecimiento_id' => $establecimiento->id]);
            }

            //


            return view('external.callManager.index', compact([
                'control_actual',
                'control_sesion_actual',
                'tipo_eventos',
                'emisors',
                'receptors',
                'eventos_sesion_push',
                'documento_anverso',
                'documento_reverso',
                'pagos_sesion',
                'tarjetas_sesion',
                'establecimiento',
                'ayudaStepTotem',
                'imagenes',
                'imagenes_tour',
                'zonas_comunes',
                'incidencias_abiertas',
                'error_api',
                'pms_active',
            ]));
        } else {

            return view('external.callManager.index');
        }
    }

    public function recommendations(Request $request)
    {
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientoId = session('api_establecimiento_id');
        if (empty($establecimientoId)) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay establecimiento activo en sesión.',
            ], 422);
        }

        $baseUrl = (string) (config('services.ari.base_url') ?: config('app.url'));
        $url = rtrim($baseUrl, '/') . '/api/v1/ari/debug/hotel-initial-recommendation';
        $token = (string) config('services.ari.token');

        if ($token === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Falta configurar el token de ARI (`ARI_API_TOKEN`).',
            ], 500);
        }

        $payload = [
            'external_id' => (string) $establecimientoId,
            // Forzar a ARI a devolver la última versión (evita respuestas cacheadas antiguas cuando se requiere)
            'force_latest' => true,
        ];

        try {
            $resp = Http::timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-token' => $token,
                ])
                ->post($url, $payload);

            if (!$resp->successful()) {
                Log::warning('ARI recommendation error (external)', [
                    'url' => $url,
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                    'payload' => $payload,
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'Error al obtener recomendaciones.',
                    'status' => $resp->status(),
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'data' => $resp->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('ARI recommendation exception (external)', [
                'url' => $url,
                'payload' => $payload,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'No se pudo conectar con el servicio de recomendaciones.',
            ], 502);
        }
    }

    public function reservationRecommendations(Request $request)
    {
        abort_if(Gate::denies('control_sesion_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientoId = session('api_establecimiento_id');
        if (empty($establecimientoId)) {
            return response()->json([
                'ok' => false,
                'message' => 'No hay establecimiento activo en sesión.',
            ], 422);
        }

        $token = (string) config('services.ari.token');
        if ($token === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Falta configurar el token de ARI (`ARI_API_TOKEN`).',
            ], 500);
        }

        $folio = $request->input('folio');
        $reservation = $request->input('reservation');

        if (empty($folio) && empty($reservation)) {
            return response()->json([
                'ok' => false,
                'message' => 'Faltan datos de la reserva para generar recomendaciones.',
            ], 422);
        }

        $baseUrl = (string) (config('services.ari.base_url') ?: config('app.url'));
        $url = rtrim($baseUrl, '/') . '/api/v1/ari/debug/hotel-call-recommendation';

        $payload = [
            'external_id' => (string) $establecimientoId,
            'force_latest' => true,
            'reserva_payload' => [
                'folio' => $folio,
                'reservation' => $reservation,
            ],
        ];

        // Log explícito del destino para poder verificar si estamos apuntando al host correcto
        // (muy importante si `ARI_API_BASE_URL` no está definido y cae en `APP_URL`).
        Log::info('ARI reservationRecommendations outbound (external)', [
            'url' => $url,
            'base_url' => $baseUrl,
            'payload' => $payload,
        ]);

        Log::info('ARI reservationRecommendations requested (external)', [
            'external_id' => (string) $establecimientoId,
            'folio_id' => is_array($folio) ? ($folio['id'] ?? null) : null,
            'reservation_id' => is_array($reservation) ? ($reservation['id'] ?? null) : null,
        ]);

        try {
            $resp = Http::timeout(20)
                ->acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-token' => $token,
                ])
                ->post($url, $payload);

            // Guardar también un fragmento del body para confirmar que viene de ARI (sin llenar logs)
            try {
                Log::debug('ARI reservationRecommendations response body (truncated) (external)', [
                    'status' => $resp->status(),
                    'body' => mb_substr((string) $resp->body(), 0, 2000),
                ]);
            } catch (\Throwable $e) {
                // no-op
            }

            Log::info('ARI reservationRecommendations response (external)', [
                'status' => $resp->status(),
            ]);

            if (!$resp->successful()) {
                Log::warning('ARI reservation recommendation error (external)', [
                    'url' => $url,
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                    'payload' => $payload,
                ]);

                return response()->json([
                    'ok' => false,
                    'message' => 'Error al obtener recomendaciones de la reserva.',
                    'status' => $resp->status(),
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'data' => $resp->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('ARI reservation recommendation exception (external)', [
                'url' => $url,
                'payload' => $payload,
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'No se pudo conectar con el servicio de recomendaciones de reserva.',
            ], 502);
        }
    }


    public function cerrar(Request $request)
    {

        $sesion_actual = ControlSesion::where('emisor_id', $request->emisor_id)
            ->where('estado_sesion', 1)
            ->update(['estado_sesion' => 0]);

        $sesion = collect([
            'receptor_id' => $request->receptor_id,
            'canal_transmision' => 'Home Inferior',
            'tipo_evento_id' => 0
        ]);

        $purgar_respuesta_texto = EventoHomeTotem::where('emisor_id', $request->emisor_id)
            ->update(['respuesta_texto' => '']);

        event(new SendTotemHomeEvent($sesion));

        return back();
    }

    public function edit(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emisors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receptors = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $controlSesion->load('emisor', 'receptor');

        return view('external.controlSesions.edit', compact('controlSesion', 'emisors', 'receptors'));
    }

    public function update(UpdateControlSesionRequest $request, ControlSesion $controlSesion)
    {
        $controlSesion->update($request->all());

        $prefix = request()->routeIs('external.*') ? 'external' : 'external';
        return redirect()->route($prefix . '.control-sesions.index');
    }

    public function show(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->load('emisor', 'receptor', 'sesionPagoTotems', 'sesionGrabacionTarjeta', 'sesionEventoHomeTotems', 'sesionFirmaCheckIns');

        return view('external.controlSesions.show', compact('controlSesion'));
    }

    public function destroy(ControlSesion $controlSesion)
    {
        abort_if(Gate::denies('control_sesion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlSesion->delete();

        return back();
    }

    public function massDestroy(MassDestroyControlSesionRequest $request)
    {
        $controlSesions = ControlSesion::find(request('ids'));

        foreach ($controlSesions as $controlSesion) {
            $controlSesion->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function paymentTable(Request $request, $folio_id)
    {
        $pagos_sesion = PagoTotem::with(['pagoOrigenRespuestaPagos', 'emisor'])->where('folio_id', $folio_id)->get();

        return view('external.callManager.paymentTable', compact('pagos_sesion'));
    }

    public function writeCardTable(Request $request)
    {
        $data = $request->all();
        if (isset($data['folio_id']) && !empty($data['folio_id'])) {
            $tarjetas_sesion = GrabacionTarjetum::with(['emisor'])->where('folio_id', $data['folio_id'])->get();
        } else {
            $control_actual = ControlSesion::where('emisor_id', auth()->user()->id)->where('estado_sesion', 1)->first();
            $tarjetas_sesion = GrabacionTarjetum::with(['emisor'])->where('sesion_id', $control_actual->id)->get();
        }

        return view('external.callManager.writeCardTable', compact('tarjetas_sesion'));
    }

    public function documentView(Request $request)
    {

        //$control_actual = ControlSesion::where('emisor_id', auth()->user()->id)->where('estado_sesion', 1)->first();

        $hotel = Establecimiento::where('id', session('api_establecimiento_id'))->first();

        $foto = EventoHomeTotem::where('objeto', $request->objeto)
            ->where('tipo_evento_id', $request->tipo_evento_id)
            ->where('pms', $hotel->api_pms)
            //->where('sesion_id', $control_actual->id)
            ->orderBy('id', 'DESC')
            ->first();


        if ($request->destino == 'small') {
            if (!empty($foto->respuesta_texto)) {
                return '<img src="' . $foto->respuesta_texto . '" class="img-fluid border rounded" style="cursor: pointer" onclick="foto_documento_ampliar(' . $request->objeto . ', ' . $request->tipo_evento_id . ', this)" />';
            } else {
                if ($request->tipo_evento_id == 6) {
                    return '<img src="/img/no_docu_2.png" class="img-fluid" />';
                } else {
                    return '<img src="/img/no_docu_1.png" class="img-fluid" />';
                }
            }
        } else {
        }
    }

    public function signView(Request $request)
    {

        //$control_actual = ControlSesion::where('emisor_id', auth()->user()->id)->where('estado_sesion', 1)->first();

        $hotel = Establecimiento::where('id', session('api_establecimiento_id'))->first();

        $foto = EventoHomeTotem::where('objeto', $request->objeto)
            ->where('tipo_evento_id', $request->tipo_evento_id)
            ->where('pms', $hotel->api_pms)
            ->orderBy('id', 'DESC')
            // ->where('sesion_id', $control_actual->id)
            ->first();


        if ($request->destino == 'small') {
            if (!empty($foto->respuesta_texto)) {
                return '<img src="' . $foto->respuesta_texto . '" class="img-fluid border rounded" style="cursor: pointer" onclick="foto_documento_ampliar(' . $request->objeto . ', ' . $request->tipo_evento_id . ', this)" />';
            } else {
                return '<img src="/img/no_firma.jpg" class="img-fluid" />';
            }
        } else {
        }
    }


    public function reservationResumen(Request $request, $id)
    {
        $data = $request->all();

        if ($data['pms'] != 'local') {
            $reservation = Reservation::where('remote_id', $id)
                ->where('pms',$data['pms'])
                ->first();
            if (!empty($data['checkin_partner_id'])) {
                $checkIns = CheckIn::where('reservation_id', $reservation->id)
                    ->where('remote_id', $data['checkin_partner_id'])
                    ->get();
            } else {
                $checkIns = CheckIn::where('reservation_id', $reservation->id)->get();
            }
        } else {
            $reservation = Reservation::where('id', $id)->first();
            if (!empty($data['checkin_partner_id'])) {
                $checkIns = CheckIn::where('reservation_id', $reservation->id)
                    ->where('id', $data['checkin_partner_id'])
                    ->get();
            } else {
                $checkIns = CheckIn::where('reservation_id', $reservation->id)->get();
            }
        }

        $emailOptions = true;

        return view('frontend.eventoHomeTotems.reservation', compact('reservation', 'checkIns', 'emailOptions'));
    }

    public function parteSendMail(Request $request)
    {
        $data = $request->all();
        $errores = [];

        // Validar campos requeridos
        if (empty($data['email'])) {
            $errores[] = 'Falta el campo "email".';
        }

        if (empty($data['reservation_id'])) {
            $errores[] = 'Falta el campo "reservation_id".';
        } elseif (!is_numeric($data['reservation_id'])) {
            $errores[] = 'El campo "reservation_id" debe ser numérico.';
        }

        if (!array_key_exists('parte_viajero', $data)) {
            $errores[] = 'Falta el campo "parte_viajero".';
        }

        if (!array_key_exists('recibo_pago', $data)) {
            $errores[] = 'Falta el campo "recibo_pago".';
        }

        if (!empty($errores)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Errores en los datos recibidos.',
                'errors' => $errores
            ], 400);
        }

        // Extraer datos
        $emails = array_map('trim', explode(",", $data['email']));
        $reservation_id = (int) $data['reservation_id'];
        $parte_viajero_mail = $data['parte_viajero'] === "true";
        $recibo_pago_mail = $data['recibo_pago'] === "true";

        // Buscar reserva
        $reservation = Reservation::find($reservation_id);
        if (!$reservation) {
            return response()->json([
                'status' => 'error',
                'message' => "No se encontró la reserva con ID: $reservation_id"
            ], 404);
        }
        $establecimiento_datos = [];
        $sesion_actual = ControlSesion::where('emisor_id', Auth::user()->id)->
            where('estado_sesion', 1)->first();
        if(isset($sesion_actual->id)){
            $totem_sesion_user = User::where('id', $sesion_actual->receptor_id)->first();
            if(isset($totem_sesion_user->id)){
                $totem_sesion_establecimiento = Totem::where('id', $totem_sesion_user->totem_id)->first();
                if(isset($totem_sesion_establecimiento->id)){
                    $totem_establecimiento = Establecimiento::where('id', $totem_sesion_establecimiento->establecimiento_id)->first();
                    if(isset($totem_establecimiento->id)){
                        $establecimiento_datos = $totem_establecimiento;
                    }
                }
            }
        }


        // Obtener los check-ins directamente sin relaciones
        $checkIns = CheckIn::where('reservation_id', $reservation_id)->get();


        if ($checkIns->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron registros de check-in para esta reserva.'
            ], 400);
        }

        try {
            Mail::to($emails)->send(
                new ParteViajeroMail($reservation, $establecimiento_datos, $checkIns, $parte_viajero_mail, $recibo_pago_mail)
            );
            return response()->json(['status' => 'ok'], 200);
        } catch (\Throwable $e) {
            \Log::error('Error al enviar parte viajero: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al enviar el email.',
            ], 500);
        }
    }
    public function llamadaInversa(Request $request)
    {
        $emisors = auth()->user();

        if (empty($emisors->sip_identity)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No está definido el SIP Identity para el usuario.'
            ], 200);
        }//

        $sesion = ControlSesion::where('emisor_id', $emisors->id)
            ->where('estado_sesion', 1)->first();

        if (!isset($sesion->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay una sesión activa con un tótem asignado.'
            ], 200);
        }

        $event = [
            'tipo_evento_id' => 'reverse_call',
            'sesion_id'      => $sesion->id,
            'receptor_id'    => $sesion->receptor_id,
            'emisor_id'      => $sesion->emisor_id,
            'canal_transmision' => 'Home Inferior',
            'mensaje'        => $emisors->sip_identity
        ];
        event(new SendTotemHomeEvent($event));

        return response()->json([
            'status' => 'ok',
            'message' => 'Llamada inversa solicitada correctamente.'
        ], 200);
    }

    public function huespedRelacion(Request $request)
    {
        $data = $request->all();
        return response()->json([
            'status' => 'ok',
            'data' => $data
        ], 200);
    }

    public function cerrarParteViajeroTotem(Request $request)
    {
        $emisors = auth()->user();

        $sesion = ControlSesion::where('emisor_id', $emisors->id)
            ->where('estado_sesion', 1)->first();


        if (isset($sesion->id)) {
            $event = [
                'tipo_evento_id' => 'cerrar_parte_viajero',
                'sesion_id'      => $sesion->id,
                'receptor_id'    => $sesion->receptor_id,
                'emisor_id'    => $sesion->emisor_id,
                'canal_transmision' => 'Home Inferior',
                'mensaje' => ''
            ];
            event(new SendTotemHomeEvent($event));
        }
    }

    public function huespedInfo(Request $request)
    {
        $data = $request->all();
        $hotel_api = new HotelApiService(session('api_establecimiento_id'));
        $partner = $hotel_api->getClientInfo($data);
        if ($partner) {
            return response()->json([
                'status' => 'ok',
                'message' => 'huesped encontrado',
                'data' => $partner
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'huesped no encontrado',
        ], 400);
    }
}
