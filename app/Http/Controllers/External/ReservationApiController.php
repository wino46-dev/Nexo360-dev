<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\ControlSesion;
use App\Services\HotelApiService;
use App\Services\UtilService;
use App\Events\TotemReservationLoadEvent;
use Illuminate\Support\Arr;
use App\Services\ParteViajeroService;
use App\Models\ControlSesion as ControlSesionModel;
use App\Models\CheckIn;
use App\Models\Establecimiento;
use App\Models\Reserva;
use App\Models\GrabacionTarjetum;
use App\Models\PagoTotem;
use App\Models\EventoHomeTotem;
use App\Models\Folio;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Services\PmsErrorFormatter;

class ReservationApiController extends Controller
{
    public function folioSearch(Request $request)
    {
        // Enforce allowed hotel for external users based on current session establecimiento
        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }

        $current_user_sesion = ControlSesion::where('estado_sesion', 1)->where('emisor_id', auth()->user()->id)->with(['receptor'])->first();
        if (!isset($current_user_sesion->receptor_id)) {
            return 'error receptor_id';
        }
        $usuarioTotem = User::where('id', $current_user_sesion->receptor_id)->with(['totem'])->first();
        if (!isset($usuarioTotem->totem->id)) {
            return 'error totem_id';
        }
        $remote_hotel_id = $usuarioTotem->totem->establecimiento->remote_hotel_id;

        if (empty($remote_hotel_id)) {
            return response()->json(['error' => 'No esta definido el Remote ID, para este establecimiento'], 500);
        }
        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        if ($request->has('q')) {
            $data = [
                'q' => $request->input('q') ?? null,
                //'remote_hotel_id' => $remote_hotel_id,
                'date_start' => $request->input('date_start') ?? null,
                'date_end' => $request->input('date_end') ?? null,
            ];
            $folios = $hotel_api->folioSearch($data);
        } else {
            $data = [
                'type' => $request->input('type') ?? null
            ];
            $folios = $hotel_api->folioSearch($data);
        }

        if (isset($folios['error'])) {
            // Formatear mensaje para UI (máx 600 chars) manteniendo datos originales como 'raw'
            $msg = PmsErrorFormatter::forUi(
                'Error al buscar reservas en el PMS',
                ($folios['message'] ?? ($folios['description'] ?? null)),
                600
            );
            return response()->json(['error' => $msg, 'raw' => $folios], 500);
        }

        return response()->json(['data' => $folios]);
    }

    public function folioDetail(Request $request)
    {
        $id = $request->input('id') ?? null;

        if (!$id) {
            return null;
        } else {
            $sid = session('api_establecimiento_id');
            $allowed = config('external.allowed_hotels');
            if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
                abort(403, 'Hotel no autorizado para este usuario.');
            }
            $hotel_api = new HotelApiService($sid);
            $folio = $hotel_api->folioDetail($id);

            return view('external.callManager.reservation.folioDetail', compact('folio'));
        }
    }

    public function folioDetailSelect(Request $request)
    {
        $id = $request->id ?? null;

        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }
        $hotel_api = new HotelApiService($sid);

        $folio = $hotel_api->folioDetail($id);

        if (!isset($folio['reservations'][0])) {
            return response()->json(['error' => 'Esta reserva no tiene habitación'], 500);
        }
        $reservation = $folio['reservations'][0];

        //$hotel_api = new HotelApiService(session('api_establecimiento_id'));

        /* Cargamos la primera habitación para mostrar en el totem */
        $reservation = $hotel_api->reservationDetail($reservation['id']);

        $data = collect([
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'sesion_id' => $request->sesion_id,
            'reserva' => $reservation,
        ]);
        event(new TotemReservationLoadEvent($data));

        return view('external.callManager.reservation.folioDetail', ['folio' => $folio, 'origen' => 'folio_selected']);
    }

    public function reservationSelectOld(Request $request, $id)
    {

        $reservation_id = $id;
        $establecimiento = Establecimiento::find(session('api_establecimiento_id'));

        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        $reservation = $hotel_api->reservationDetail($reservation_id);

        $checkinPartners = $hotel_api->reservationCheckinPartners($reservation_id);


        $documentTypesList = Arr::pluck($hotel_api->documentTypes(), 'documentType', 'id');

        $countriesList = Arr::pluck(UtilService::countries_all(), 'country', 'id');

        //array_unshift($countriesList, 'Seleccione...');
        //dump($checkinPartners);
        $gendersList = $hotel_api->genders();

        $countryStateList = [];

        foreach ($checkinPartners as &$partner) {

            $birthdate_por = explode("T", $partner['birthdate']);
            $partner['birthdate'] = $birthdate_por[0];

            $documentExpeditionDate_por = explode("T", $partner['documentExpeditionDate']);
            $partner['documentExpeditionDate'] = $documentExpeditionDate_por[0];

            $date_format = UtilService::determinarFormatoFecha($partner['birthdate']);
            if ($date_format == 'dd/mm/YYYY') {
                $fechaDateTime = Carbon::createFromFormat('d/m/Y', $partner['birthdate']);
                $partner['birthdate'] = $fechaDateTime->format('Y-m-d');
            }

            $date_format = UtilService::determinarFormatoFecha($partner['documentExpeditionDate']);
            if ($date_format == 'dd/mm/YYYY') {
                $fechaDateTime = Carbon::createFromFormat('d/m/Y', $partner['documentExpeditionDate']);
                $partner['documentExpeditionDate'] = $fechaDateTime->format('Y-m-d');
            }
            $partner['countryStateList'] = [];
            if (!empty($partner['countryState'])) {
                $countryStateList = $hotel_api->country_states($partner['countryId']);
                $partner['countryStateList'] = Arr::pluck($countryStateList, 'name', 'id');
            }

            $partner['checkin_ok'] = false;
            $filePathPdf = public_path('parte-viajero/parte_viajero_' . $partner['id'] . '.pdf');
            if (file_exists($filePathPdf)) {
                $partner['checkin_ok'] = true;
            }
        }

        //Log::info('$checkinPartners');
        //Log::info($checkinPartners);

        $control_actual = ControlSesion::where('emisor_id', auth()->user()->id)->where('estado_sesion', 1)->first();

        $data = collect([
            'emisor_id' => $control_actual->emisor_id,
            'receptor_id' => $control_actual->receptor_id,
            'sesion_id' => $control_actual->sesion_id,
            'reserva' => $reservation,
        ]);

        event(new TotemReservationLoadEvent($data));

        return view(
            'external.callManager.reservation.checkin',
            compact(
                'reservation',
                'checkinPartners',
                'documentTypesList',
                'gendersList',
                'countriesList',
                'countryStateList',
                'reservation_id',
                'control_actual',
                'establecimiento'
            )
        );
    }

    public function reservationSelect(Request $request, $id)
    {
        // Validación temprana del ID para evitar 500 con entradas inválidas
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Parámetro ID de reserva inválido'], 400);
        }
        $reservation_id = (int) $id;
        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }
        $establecimiento = Establecimiento::find($sid);

        $hotel_api = new HotelApiService($sid);

        $reservation = $hotel_api->reservationDetail($reservation_id);
        $checkinPartners = $hotel_api->reservationCheckinPartners($reservation_id);

        $documentTypesList = Arr::pluck($hotel_api->documentTypes(), 'documentType', 'id');
        $countriesList = Arr::pluck(UtilService::countries_all(), 'country', 'id');
        $gendersList = $hotel_api->genders();

        $countryStateList = [];

        foreach ($checkinPartners as &$partner) {
            // Aseguramos birthdate
            if (!empty($partner['birthdate'])) {
                $birthdate_por = explode("T", $partner['birthdate']);
                $partner['birthdate'] = $birthdate_por[0];

                $date_format = UtilService::determinarFormatoFecha($partner['birthdate']);
                if ($date_format == 'dd/mm/YYYY') {
                    $fechaDateTime = Carbon::createFromFormat('d/m/Y', $partner['birthdate']);
                    $partner['birthdate'] = $fechaDateTime->format('Y-m-d');
                }
            }

            // Aseguramos documentExpeditionDate
            if (!isset($partner['documentExpeditionDate']) || empty($partner['documentExpeditionDate'])) {
                $partner['documentExpeditionDate'] = '1999-01-01';
            } else {
                $documentExpeditionDate_por = explode("T", $partner['documentExpeditionDate']);
                $partner['documentExpeditionDate'] = $documentExpeditionDate_por[0];

                $date_format = UtilService::determinarFormatoFecha($partner['documentExpeditionDate']);
                if ($date_format == 'dd/mm/YYYY') {
                    $fechaDateTime = Carbon::createFromFormat('d/m/Y', $partner['documentExpeditionDate']);
                    $partner['documentExpeditionDate'] = $fechaDateTime->format('Y-m-d');
                }
            }

            $partner['countryStateList'] = [];
            if (!empty($partner['countryState'])) {
                $countryStateList = $hotel_api->country_states($partner['countryId']);
                $partner['countryStateList'] = Arr::pluck($countryStateList, 'name', 'id');
            }

            $partner['checkin_ok'] = false;
            $filePathPdf = public_path('parte-viajero/parte_viajero_' . $partner['id'] . '.pdf');
            if (file_exists($filePathPdf)) {
                $partner['checkin_ok'] = true;
            }
        }

        $control_actual = ControlSesion::where('emisor_id', auth()->user()->id)
            ->where('estado_sesion', 1)
            ->first();

        $data = collect([
            'emisor_id' => $control_actual->emisor_id,
            'receptor_id' => $control_actual->receptor_id,
            'sesion_id' => $control_actual->sesion_id,
            'reserva' => $reservation,
        ]);

        event(new TotemReservationLoadEvent($data));

        return view(
            'external.callManager.reservation.checkin',
            compact(
                'reservation',
                'checkinPartners',
                'documentTypesList',
                'gendersList',
                'countriesList',
                'countryStateList',
                'reservation_id',
                'control_actual',
                'establecimiento'
            )
        );
    }

    public function countriesStates(Request $request, $id)
    {
        if (empty($id)) {
            return response()->json([]);
        }
        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }
        $hotel_api = new HotelApiService($sid);
        $countriesList = $hotel_api->country_states($id);
        return response()->json($countriesList);
    }

    public function checkinPartner(Request $request)
    {
        $data = $request->all();

        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }

        $emisors = auth()->user()->id;

        $current_user_sesion = ControlSesionModel::where('estado_sesion', 1)->where('emisor_id', $emisors)->with(['receptor'])->first();
        $usuarioTotem = User::where('id', $current_user_sesion->receptor_id)->with(['totem'])->first();

        $reservation_id = $data['reservation_id'];

        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        $reservation = $hotel_api->reservationDetail($reservation_id);

        $reservation['establecimiento_id'] = session('api_establecimiento_id');

        // Asegurar campo obligatorio 'name' si no viene informado
        if (empty($data['name'])) {
            $parts = array_filter([
                trim((string)($data['firstname'] ?? '')),
                trim((string)($data['lastname'] ?? '')),
                trim((string)($data['lastname2'] ?? '')),
            ], function($v){ return $v !== ''; });
            if (!empty($parts)) {
                $data['name'] = implode(' ', $parts);
            }
        }

        $checkin_partner = $hotel_api->checkinPartnerSave($data, $reservation);

        if (isset($data['action']) && $data['action'] == 'signature_capture') {
            return response()->json([
                'success' => 'Solicitando Firma',
            ]);
        }
        if (isset($data['action']) && $data['action'] == 'checkin_save') {

            $firma_error = [];
            if (!is_array($checkin_partner)) {
                $firma_valida = EventoHomeTotem::where('objeto', $checkin_partner)
                    ->where('tipo_evento_id', 4)
                    ->where('pms', $hotel_api->pms)
                    ->first();
                if (!$firma_valida || empty($firma_valida['respuesta_texto'])) {
                    $firma_error[] = $data['firstname'] . ' ' . $data['lastname'];
                }

                if (count($firma_error)) {
                    $hotel_api->faltaFirma($checkin_partner);
                    return response()->json([
                        'error' => 'Falta la firma de: ' . implode(", ", $firma_error),
                    ], 422);
                }
            }

            if (!empty($checkin_partner['error'])) {
                $short = PmsErrorFormatter::forUi(
                    'Error al validar documento. Revise tipo, número y país del documento',
                    (string)($checkin_partner['message'] ?? ''),
                    600
                );
                return response()->json(['error' => $short], 422);
            } else {
                // Forzar recálculo de costes/resumen económico en PMS (get reservation)
                try {
                    \Log::info('[checkin_partial][recalc][external] start', ['reservation_id' => (int)$reservation_id]);
                    $hotel_api->reservationDetail((int)$reservation_id);
                } catch (\Throwable $e) {
                    \Log::warning('[checkin_partial][recalc][external] fail', ['reservation_id' => (int)$reservation_id, 'err' => $e->getMessage()]);
                }
                ParteViajeroService::pdfGenerate($reservation_id, $checkin_partner, $hotel_api->pms);

                return response()->json([
                    'success' => 'Checking completado para <b>' . $data['firstname'] . ' ' . $data['lastname'] . '</b>',
                    'reservation_id' => intval($reservation_id),
                    'checkin_partner_id' => $checkin_partner
                ]);
            }
        }
    }



    public function checkin(Request $request)
    {
        $data = $request->all();

        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }

        $emisors = auth()->user()->id;

        $current_user_sesion = ControlSesionModel::where('estado_sesion', 1)->where('emisor_id', $emisors)->with(['receptor'])->first();
        $usuarioTotem = User::where('id', $current_user_sesion->receptor_id)->with(['totem'])->first();

        $reservation_id = $data['partners'][0]['reservation_id'];

        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        $reservation = $hotel_api->reservationDetail($reservation_id);

        $reservation['establecimiento_id'] = $usuarioTotem->totem->establecimiento_id;

        if ($data['opc'] == 'validate_pago') {

            $firma_error = [];

            foreach ($data['partners'] as $partner) {
                $firma_valida = EventoHomeTotem::where('objeto', $partner['id'])
                    ->where('tipo_evento_id', 4)
                    ->where('pms', $hotel_api->pms)
                    ->orderByDesc('id') // o 'created_at' si lo prefieres
                    ->first();

                if (!$firma_valida || empty($firma_valida['respuesta_texto'])) {
                    $firma_error[] = $partner['firstname'] . ' ' . $partner['lastname'];
                }
            }
            if (count($firma_error)) {
                return response()->json([
                    'error' => 'Falta la(s) firma de: ' . implode(", ", $firma_error),
                ], 422);
            }

            $folio = $hotel_api->folioDetail($reservation['folioId']);

            $pendingAmount = $folio['pendingAmount'];
            $confirm_text = '';
            if (floatval($pendingAmount) > 0) {
                $confirm_text = "Esta reserva no ha sido abonada en su totalidad. Compruebe las condiciones de pago antes de facilitar el acceso <br />";
                /*Log::info('saldo ' . $pendingAmount);

                $pagos_sesion = PagoTotem::where('folio_id', $reservation['folioId'])
                    ->where('estado', 'Autorizada')
                    ->sum('importe');
                Log::info('pagos ' . $pagos_sesion);

                if (!empty(floatval($pendingAmount))) {
                //if (floatval($pendingAmount) > floatval($pagos_sesion)) {
                    $saldo = floatval($pendingAmount) - floatval($pagos_sesion);

                    $confirm_text = "Esta reserva no ha sido abonada en su totalidad. Compruebe las condiciones de pago antes de facilitar el acceso <br /><br /><br />";
                    //return response()->json(['error' => 'Existe saldo pendiente de pago (Saldo: ' . $saldo . ')'], 422);
                }*/
            }

            if (!empty($confirm_text)) {
                return response()->json(['confirm' => $confirm_text], 422);
            }
            return response()->json(['success' => 'ok']);
        }

        if ($data['opc'] == 'validate') {

            $confirm_text = '';

            $tarjetas_sesion = GrabacionTarjetum::where('folio_id', $reservation['folioId'])
                ->where('status', 'Ok')
                ->get();

            if (!count($tarjetas_sesion)) {
                $confirm_text .= 'No se han grabado las tarjeta de acceso';
            }
            if (!empty($confirm_text)) {
                return response()->json(['confirm' => $confirm_text], 422);
            }
            return response()->json(['success' => 'ok']);
        }

        if ($data['opc'] == 'save') {

            foreach ($data['partners'] as $partner) {
                //dd($partner);
                $partner['action'] = 'checkin_save';
                // Asegurar campo obligatorio 'name' si no viene informado
                if (empty($partner['name'])) {
                    $parts = array_filter([
                        trim((string)($partner['firstname'] ?? '')),
                        trim((string)($partner['lastname'] ?? '')),
                        trim((string)($partner['lastname2'] ?? '')),
                    ], function($v){ return $v !== ''; });
                    if (!empty($parts)) {
                        $partner['name'] = implode(' ', $parts);
                    }
                }
                try {
                    $checkin_partner = $hotel_api->checkinPartnerSave($partner, $reservation);

                    if (!empty($checkin_partner['error'])) {
                        $short = PmsErrorFormatter::forUi(
                            'Error al completar check-in',
                            (string)($checkin_partner['message'] ?? ''),
                            600
                        );
                        return response()->json(['error' => $short], 500);
                    }
                } catch (\Exception $e) {
                    // return response()->json(['error' => 'Error updating user: ' . $e->getMessage()], 500);
                    echo 'Error: ' . $e->getMessage();
                }
            }

            ParteViajeroService::pdfGenerate($reservation_id, null, $hotel_api->pms);

            return [
                'id' => $reservation_id,
                'success' => 'Checking completato'
            ];
        }
        return response()->json(['error' => 'Error sin opción'], 500);
    }

    public function folioStatus(Request $request)
    {
        $hotel_api = new HotelApiService(session('api_establecimiento_id'));

        $error_message = null;
        $err_checkin = null;
        $err_checkout = null;

        try {
            $reservations_checkin_status = $hotel_api->getReservationsStatus(['type' => 'checkin']);
            if (is_array($reservations_checkin_status) && isset($reservations_checkin_status['error'])) {
                $status = $reservations_checkin_status['status'] ?? '';
                $msg    = $reservations_checkin_status['message'] ?? 'Error al cargar el estado de reservas.';
                $hint   = $reservations_checkin_status['hint'] ?? '';
                $err_checkin = trim("[$status] $msg\n$hint");
                $reservations_checkin_status = [];
            }
        } catch (\Exception $e) {
            $err_checkin = $e->getMessage();
            $reservations_checkin_status = [];
        }

        try {
            $reservations_checkout_status = $hotel_api->getReservationsStatus(['type' => 'checkout']);
            if (is_array($reservations_checkout_status) && isset($reservations_checkout_status['error'])) {
                $status = $reservations_checkout_status['status'] ?? '';
                $msg    = $reservations_checkout_status['message'] ?? 'Error al cargar el estado de reservas.';
                $hint   = $reservations_checkout_status['hint'] ?? '';
                $err_checkout = trim("[$status] $msg\n$hint");
                $reservations_checkout_status = [];
            }
        } catch (\Exception $e) {
            $err_checkout = $e->getMessage();
            $reservations_checkout_status = [];
        }

        // Unificar mensajes para evitar duplicados
        if ($err_checkin && $err_checkout) {
            if ($err_checkin === $err_checkout) {
                $error_message = 'Error al cargar el estado de reservas (check-in y check-out): ' . $err_checkin;
            } else {
                $error_message = 'Error al cargar el estado de reservas (check-in): ' . $err_checkin . "\n"
                    . 'Error al cargar el estado de reservas (check-out): ' . $err_checkout;
            }
        } elseif ($err_checkin) {
            $error_message = 'Error al cargar el estado de reservas (check-in): ' . $err_checkin;
        } elseif ($err_checkout) {
            $error_message = 'Error al cargar el estado de reservas (check-out): ' . $err_checkout;
        }

        return view(
            'external.callManager.reservation._status_reservations',
            compact('reservations_checkin_status', 'reservations_checkout_status', 'error_message')
        );
    }

    /**
     * Devuelve el HTML con el desglose de costes de una reserva (solo lectura / debug temporal)
     */
    public function reservationCosts(Request $request, $id)
    {
        // Validación básica
        if (!is_numeric($id)) {
            return response()->json(['error' => 'ID de reserva inválido'], 400);
        }

        $sid = session('api_establecimiento_id');
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed) && $sid && !in_array((int)$sid, array_map('intval', $allowed))) {
            abort(403, 'Hotel no autorizado para este usuario.');
        }

        $hotel_api = new \App\Services\HotelApiService($sid);
        $reservation = $hotel_api->reservationDetail((int)$id);

        // LOG diagnóstico temporal
        try {
            $numServices = (int)($reservation['numServices'] ?? 0);
            $services = $reservation['services'] ?? [];
            $boardServices = $reservation['boardServices'] ?? [];
            $hasArrays = (!empty($services) || !empty($boardServices));
            \Log::info('[reservationCosts] load', [
                'sid' => $sid,
                'reservation_id' => (int)$id,
                'numServices' => $numServices,
                'services_count' => is_array($services) ? count($services) : 0,
                'boardServices_count' => is_array($boardServices) ? count($boardServices) : 0,
                'hasArrays' => $hasArrays,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[reservationCosts] log error: '.$e->getMessage());
        }

        if (isset($reservation['error'])) {
            \Log::error('[reservationCosts] API error', ['reservation_id' => (int)$id, 'error' => $reservation['error'] ?? null]);
            return response()->json(['error' => $reservation['error']], 500);
        }

        return view('external.callManager.reservation.costBreakdown', compact('reservation'));
    }
}
