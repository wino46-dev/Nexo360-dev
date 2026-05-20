<?php

namespace App\Services;

use App\Models\CheckIn;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Exception;

use App\Models\ControlError;
use App\Models\Folio;
use App\Models\Reservation;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;

use App\Services\Contracts\HotelPmsInterface;

class RoomdooApiService implements HotelPmsInterface
{
    public $hotel_id;
    public $api_pms, $api_pms_url, $api_pms_username, $api_pms_password, $api_pms_hotel_id;
    public $user_login = null;
    public $room_types_list = null;
    public $price_list_id = null;
    public $using_user_credentials = false;
    public $auth_cache_key = null;

    public function resetAuthCache(): void
    {
        // Clear possible legacy and current cache keys
        $base = 'roomdoo_login_' . $this->hotel_id;
        Cache::forget($base); // legacy key used previously
        Cache::forget($base . '_hotel');
        if (auth()->id()) {
            Cache::forget($base . '_user_' . auth()->id());
        }
        // Also clear the active computed key if set
        if ($this->auth_cache_key) {
            Cache::forget($this->auth_cache_key);
        }
    }


    public function __construct($hotel, $login_hotel = false)
    {
        $this->hotel_id = $hotel->id;
        $this->api_pms = $hotel->api_pms;
        $this->api_pms_url = $hotel->api_pms_url;
        $this->api_pms_username = $hotel->api_pms_username;
        $this->api_pms_password = $hotel->api_pms_password;
        $this->api_pms_hotel_id = $hotel->remote_hotel_id;
        $this->price_list_id = 3;

        $user_current = User::where('id', auth()->user()->id ?? null)->first();

        // Por defecto usamos las credenciales del ESTABLECIMIENTO.
        // Si el usuario NO tiene marcado "login_pms_establecimiento" y tiene pms_password, usamos sus credenciales personales.
        if (!$login_hotel && $user_current && empty($user_current->login_pms_establecimiento) && !empty($user_current->pms_password)) {
            $this->api_pms_username = $user_current->email;
            $this->api_pms_password = $user_current->pms_password;
            $this->using_user_credentials = true;
        } else {
            // Forzamos credenciales del hotel
            $this->api_pms_username = $hotel->api_pms_username;
            $this->api_pms_password = $hotel->api_pms_password;
            $this->using_user_credentials = false;
        }

        // Clave de caché diferenciada por hotel y tipo de credenciales
        $this->auth_cache_key = 'roomdoo_login_' . $this->hotel_id . ($this->using_user_credentials && $user_current ? ('_user_' . $user_current->id) : '_hotel');
    }

    public function login()
    {
        //Log::info('login hotel:' . $this->api_pms_hotel_id);
        Log::info('[RoomdooApiService] Intentando login', [
            'hotel_id' => $this->hotel_id,
            'using' => $this->using_user_credentials ? 'user_credentials' : 'hotel_credentials',
            'username' => $this->api_pms_username,
        ]);
        $json = [
            'username' => $this->api_pms_username,
            'password' => $this->api_pms_password,
        ];

        $response = Http::post($this->api_pms_url . 'login', $json);

        $statusCode = $response->status();
        $res = $response->json();

        if ($statusCode == '200') {
            $payload = $response->json();
            try {
                $tokenPropertyId = $payload['defaultPropertyId'] ?? null;
                if ($tokenPropertyId && $this->api_pms_hotel_id && (intval($tokenPropertyId) !== intval($this->api_pms_hotel_id))) {
                    Log::warning('[RoomdooApiService] Mismatch entre defaultPropertyId del token y remote_hotel_id configurado', [
                        'hotel_id' => $this->hotel_id,
                        'configured_remote_hotel_id' => $this->api_pms_hotel_id,
                        'token_default_property_id' => $tokenPropertyId,
                        'username_used' => $this->api_pms_username,
                    ]);
                }
            } catch (\Throwable $e) {
                // ignore
            }
            return $payload;
        } else {

            $msg = strip_tags((string)($res['name'] ?? 'Login Error'));
            $msg = Str::limit($msg, 200, '…');
            ControlError::create([
                'origen' => 'RoomdooApiService',
                'tipo' => $statusCode,
                'mensaje' => $msg,
                'descripcion' => json_encode($response->json()),
                'establecimiento_id' => $this->hotel_id ?? null
            ]);
            throw new Exception($statusCode . ' ' . ($res['name'] ?? 'Login Error'));
        }
    }

    public function callApi($method, $url, $data = null)
    {
        // Normalize method
        $method = strtolower($method);

        // Retrieve login from cache or refresh if missing or errored
        if (!$this->auth_cache_key) {
            $this->auth_cache_key = 'roomdoo_login_' . $this->hotel_id . ($this->using_user_credentials ? ('_user_' . (auth()->id() ?? 'guest')) : '_hotel');
        }
        $cacheKey = $this->auth_cache_key;
        $this->user_login = Cache::remember($cacheKey, 300, function () {
            return $this->login();
        });

        if (empty($this->user_login) || isset($this->user_login['error'])) {
            Cache::forget($cacheKey);
            $this->user_login = $this->login();
        }

        $token = $this->user_login['token'] ?? null;
        $url_base = rtrim($this->api_pms_url, '/');
        $fullUrl = $url_base . '/' . ltrim($url, '/');

        // Logging (mask token)
        $masked = $token ? (substr($token, 0, 8) . '...' . substr($token, -6)) : 'null';
        Log::info('[RoomdooApiService] callApi', [
            'method' => strtoupper($method),
            'url' => $fullUrl,
            'has_token' => $token ? true : false,
            'auth_header' => $token ? ('Bearer ' . $masked) : 'none',
            'data' => $data,
        ]);

        // Build request with token
        $request = Http::withToken($token)->acceptJson();

        // Execute request by method
        $response = null;
        if ($method === 'get') {
            $response = $request->get($fullUrl);
        } elseif ($method === 'post') {
            $response = $request->asJson()->post($fullUrl, $data ?? []);
        } elseif ($method === 'patch') {
            $response = $request->asJson()->patch($fullUrl, $data ?? []);
        } elseif ($method === 'put') {
            $response = $request->asJson()->put($fullUrl, $data ?? []);
        } else {
            throw new Exception('Metodo no definido: ' . $method);
        }

        // If unauthorized, token might have expired; refresh and retry once
        if ($response->status() == 401) {
            Cache::forget($cacheKey);
            $this->user_login = $this->login();
            $token = $this->user_login['token'] ?? null;
            $request = Http::withToken($token)->acceptJson();
            if ($method === 'get') {
                $response = $request->get($fullUrl);
            } elseif ($method === 'post') {
                $response = $request->asJson()->post($fullUrl, $data ?? []);
            } elseif ($method === 'patch') {
                $response = $request->asJson()->patch($fullUrl, $data ?? []);
            } elseif ($method === 'put') {
                $response = $request->asJson()->put($fullUrl, $data ?? []);
            }
        }

        $statusCode = $response->status();
        $res = $response->json();

        if ($statusCode == 200 || $statusCode == '200') {
            return $res;
        } elseif ($statusCode == 400 || $statusCode == '400') {
            $res = $response->json();
            $res['error'] = 'error';
            $res['message'] = $res['description'] ?? 'Bad Request';

            $msg = trim((string)($res['name'] ?? 'callApi Error') . ' - ' . ($res['description'] ?? '-'));
            $msg = strip_tags($msg);
            $msg = Str::limit($msg, 200, '…');
            ControlError::create([
                'origen' => 'RoomdooApiService',
                'tipo' => $statusCode,
                'mensaje' => $msg,
                'descripcion' => json_encode($response->json()),
                'establecimiento_id' => $this->hotel_id ?? null
            ]);

            return $res;
        } else {
            Log::info('Error: statusCode: ' . $statusCode);
            Log::info('response: ');
            Log::info($response);

            $mensaje = ($res['name'] ?? 'callApi Error') . ' - ' . ($res['description'] ?? '-');
            $mensaje = strip_tags((string)$mensaje);
            $mensaje = Str::limit($mensaje, 200, '…');

            ControlError::create([
                'origen' => 'RoomdooApiService',
                'tipo' => $statusCode,
                'mensaje' => $mensaje,
                'descripcion' => $response . ' - ' . json_encode($response->json()),
                'establecimiento_id' => $this->hotel_id ?? null
            ]);

            if ($statusCode == 401 || $statusCode == 403) {
                $hint = 'Acceso no autorizado a la API del PMS. Comprueba el usuario/contraseña o el token configurado para el hotel, que la URL del PMS sea correcta y que el usuario tenga acceso a la propiedad (pmsPropertyId) configurada.';
                $details = trim(($res['name'] ?? '') . ' ' . ($res['description'] ?? ''));
                // Lanzamos excepción con mensaje más claro para que pueda capturarse arriba
                throw new Exception($statusCode . ' Unauthorized - ' . ($details ?: 'Credenciales inválidas o token expirado'));
            }

            throw new Exception($statusCode . ' ' . ($res['name'] ?? 'callApi Error'));
        }
    }

    public function reservationToday($data)
    {
        $fecha_hoy = date('Y-m-d');
        //$fecha_hoy = '2025-04-02';
        // $date = '2025-03-28';
        $get = [
            'pmsPropertyId' => $this->api_pms_hotel_id,
            'filterByState' => 'pendingCheckinToday',
        ];

        $param_query = http_build_query($get);
        $folios = $this->callApi('get', 'folios' . '?' . $param_query);



        if ($data['type'] == 'checkout') {
            $field = 'checkout';
        } else {
            $field = 'checkin';
        }

        $folios_ok = [];

        foreach ($folios as $folio) {
            foreach ($folio['reservations'] as $reservation) {

                $date = Carbon::parse($reservation[$field])->format('Y-m-d');

                if (
                    $date == $fecha_hoy
                    && $reservation['reservationType'] == 'normal'
                    && $reservation['stateCode'] != 'cancel'
                ) {
                    $folios_ok[] = $folio;
                }
            }
        }

        return $folios_ok;
    }

    public function getReservationsStatus($data)
    {
        $fecha_hoy = date('Y-m-d');
        $get = [
            'pmsPropertyId' => $this->api_pms_hotel_id,
            'filterByState' => 'pendingCheckinToday',
        ];
        $param_query = http_build_query($get);
        //$folios = $this->callApi('get', 'folios' . '?' . $param_query);
        $folios = $this->callApi('get','folios?'.$param_query);

        // Propagar errores de la API de forma controlada
        if (isset($folios['error'])) {
            return $folios;
        }
        if (!is_array($folios)) {
            return [
                'error' => 'unexpected',
                'message' => 'Formato de respuesta no esperado al obtener folios',
            ];
        }
        $status_count = [
            'dummy' => 0,
            'draft' => 0,
            'precheckin' => 0,
            'onboard' => 0,
            'done' => 0,
            'cancel' => 0
        ];
        if ($data['type'] == 'checkin') {
            $field = 'checkin';
        } else {
            $field = 'checkout';
        }

        foreach ($folios as $folio) {
            Log::info('folio');
            Log::info($folio);
            foreach ($folio['reservations'] as $reservation) {


                $date = Carbon::parse($reservation[$field])->format('Y-m-d');

                if ($date == $fecha_hoy && $reservation['reservationType'] == 'normal') {
                    $partners = $this->callApi('get', 'reservations/' . $reservation['id'] . '/checkin-partners?pmsPropertyId=' . $this->api_pms_hotel_id);
                    foreach ($partners as $partner) {
                        //Log::info('partner');
                        //Log::info($partner);
                        $status_count[$partner['checkinPartnerState']] = $status_count[$partner['checkinPartnerState']] + 1;
                    }
                }
            }
        }

        return $status_count;
    }

    public function folioSearch($data)
    {

        $hoy = date('Y-m-d');
        $type = $data['type'] ?? null;
        if (isset($data['q'])) {
            $get = [
                'pmsPropertyId' => $this->api_pms_hotel_id,
                //'dateFrom' => '2024-02-01',
                //'dateTo' => '2024-02-25',
                'filter' => $data['q']
            ];
            if (!empty($data['date_start']) && !empty($data['date_end'])) {
                $get['dateFrom'] = $data['date_start'];
                $get['dateTo'] = $data['date_end'];
            }
            $param_query = http_build_query($get);
            $res = $this->callApi('get', 'folios' . '?' . $param_query);

        } elseif (isset($data['type']) && $data['type'] == 'checkout'){
            $get = [
                'pmsPropertyId' => $this->api_pms_hotel_id,
                'filter' => 'byCheckout',
            ];

            $param_query = http_build_query($get);
            $res = $this->callApi('get', 'folios' . '?' . $param_query);

        }else{
            $res = $this->reservationToday($data);
        }

        if (isset($res['error'])) {
            return $res;
        }

        // Ensure no duplicate folios by ID
        $res = collect($res)->unique('id')->values()->all();

        $result = [];
        foreach ($res as $row) {
            // 1) Formateo de fechas
            $firstCheckin2 = Carbon::parse($row['firstCheckin'])->format('d M Y');
            $lastCheckout2 = Carbon::parse($row['lastCheckout'])->format('d M Y');
            $firstCheckin3 = Carbon::parse($row['firstCheckin'])->format('Y-m-d');
            $lastCheckout3 = Carbon::parse($row['lastCheckout'])->format('Y-m-d');

            // 2) Filtrar solo las reservas que coincidan con la fecha de hoy en el campo correspondiente
            $reservas = collect($row['reservations']);
            if ($type === 'checkin' || $type === 'checkout') {
                $reservas = $reservas->filter(function($r) use ($type, $hoy) {
                    return Carbon::parse($r[$type])->format('Y-m-d') === $hoy;
                })->values();
            }

            $reservations_count = $reservas->count();
            $partners_count     = 0;
            $partners_onboard   = 0;

            // 3) Solo para esas reservas, hacer la llamada y contar partners
            foreach ($reservas as $reservation) {
                $partners = $this->callApi(
                    'get',
                    "reservations/{$reservation['id']}/checkin-partners?pmsPropertyId={$this->api_pms_hotel_id}"
                );
                foreach ($partners as $p) {
                    $partners_count++;
                    if ($p['checkinPartnerState'] === 'onboard') {
                        $partners_onboard++;
                    }
                }
            }

            $result[] = [
                'id'                 => $row['id'],
                'name'               => $row['name'],
                'partnerName'        => $row['partnerName'],
                'partnerEmail'       => $row['partnerEmail'],
                'partnerPhone'       => $row['partnerPhone'],
                'reservations_count' => $reservations_count,
                'partners_count'     => $partners_count,
                'partners_onboard'   => $partners_onboard,
                'firstCheckin'       => $row['firstCheckin'],
                'lastCheckout'       => $row['lastCheckout'],
                'firstCheckin2'      => $firstCheckin2,
                'lastCheckout2'      => $lastCheckout2,
                'firstCheckin3'      => $firstCheckin3,
                'lastCheckout3'      => $lastCheckout3,
                'state'              => $row['state'],
                'amountTotal'        => $row['amountTotal'],
                'pendingAmount'      => $row['pendingAmount'],
                'hotel_id'           => $this->hotel_id,
            ];
        }

        // 4) Ordenar y devolver
                return collect($result)
                    ->sortBy('first_checkin')
                    ->values()
                    ->all();
    }

    public function folioDetail($id)
    {
        $res = $this->callApi('get', "folios/{$id}?pmsPropertyId={$this->api_pms_hotel_id}");

        if (isset($res['error'])) {
            return $res;
        }
        $this->room_types_list = collect($this->roomTypes());

        $res['reservations'] = $this->folioReservations($id);

        $res['firstCheckin2'] = Carbon::parse($res['firstCheckin'])->format('d M Y');
        $res['lastCheckout2'] = Carbon::parse($res['lastCheckout'])->format('d M Y');

        $res['firstCheckin3'] = Carbon::parse($res['firstCheckin'])->format('Y-m-d');
        $res['lastCheckout3'] = Carbon::parse($res['lastCheckout'])->format('Y-m-d');
        $res['hotel_id'] = $this->hotel_id;

        return $res;
    }

    public function folioReservations($id)
    {
        $res = $this->callApi('get', 'folios/' . $id . '/reservations?pmsPropertyId=' . $this->api_pms_hotel_id);

        if (isset($res['error'])) {
            return $res;
        }
        foreach ($res as $k => &$row) {
            $res[$k]['checkin2'] = Carbon::parse($row['checkin'])->format('d M Y');
            $res[$k]['checkout2'] = Carbon::parse($row['checkout'])->format('d M Y');
            $res[$k]['checkin3'] = Carbon::parse($row['checkin'])->format('Y-m-d');
            $res[$k]['checkout3'] = Carbon::parse($row['checkout'])->format('Y-m-d');

            $type = $this->room_types_list->firstWhere('id', $row['roomTypeId']);
            $res[$k]['roomTypeName'] = $type['name'] ?? 'no room type';

            $lines = $this->reservationDetailLines($row['id']);

            if (!empty($lines[0]['roomId'])) {
                $room = $this->room($lines[0]['roomId']);
                $res[$k]['roomId'] = $room['id'];
                $res[$k]['roomName'] = $room['name'];
            } else {
                $res[$k]['roomId'] = null;
                $res[$k]['roomName'] = null;
            }
            $res[$k]['services'] = $this->reservationServices($row['id']);
            $res[$k]['hotel_id'] = $this->hotel_id;
        }
        return $res;
    }


    public function reservationDetail($id)
    {
        $res = $this->callApi('get', 'reservations' . '/' . $id . '?pmsPropertyId=' . $this->api_pms_hotel_id);


        if (isset($res['error'])) {
            return $res;
        }
        $res['checkin2'] = Carbon::parse($res['checkin'])->format('d M Y');
        $res['checkout2'] = Carbon::parse($res['checkout'])->format('d M Y');

        $res['checkin3'] = Carbon::parse($res['checkin'])->format('Y-m-d');
        $res['checkout3'] = Carbon::parse($res['checkout'])->format('Y-m-d');

        $lines = $this->reservationDetailLines($id);

        if (!empty($lines[0]['roomId'])) {
            $room = $this->room($lines[0]['roomId']);
            $res['roomId'] = $room['id'];
            $res['roomName'] = $room['name'];
        } else {
            $res['roomId'] = null;
            $res['roomName'] = null;
        }

        $res['services'] = $this->reservationServices($id);

        return $res;
    }

    public function reservationDetailLines($id)
    {
        $res = $this->callApi('get', 'reservations' . '/' . $id . '/reservation-lines?pmsPropertyId=' . $this->api_pms_hotel_id);

        if (isset($res['error'])) {
            return $res;
        }

        return $res;
    }

    public function reservationServices($id)
    {
        $res = $this->callApi('get', 'reservations' . '/' . $id . '/services?pmsPropertyId=' . $this->api_pms_hotel_id);

        if (isset($res['error'])) {
            return $res;
        }

        return $res;
    }


    public function room($id)
    {
        $cacheKey = 'room_' . $this->api_pms_hotel_id . '_' . $id;
        $value = Cache::remember($cacheKey, 900, function () use ($id) {
            $res = $this->callApi('get', 'rooms' . '/' . $id . '?pmsPropertyId=' . $this->api_pms_hotel_id);

            if (isset($res['error'])) {
                return $res;
            }

            return $res;
        });
        return $value;
    }

    public function reservationCheckinPartners($id)
    {
        $res = $this->callApi('get', 'reservations/' . $id . '/checkin-partners?pmsPropertyId=' . $this->api_pms_hotel_id);
        Log::info('reservationCheckinPartners');
        Log::info($res);
        if (isset($res['error'])) {
            return $res;
        }

        return $res;
    }

    public function reservationCheckinPartnersSave($data, $onBoard = false)
    {
        $reservation_id = $data['reservation_remote_id'];
        $checkin_partner_id = $data['id'];

        unset($data['reservation_id']);
        unset($data['reservation_remote_id']);

        unset($data['action']);
        if (!$onBoard) {

            unset($data['id']);
            unset($data['countryStateName']);
            unset($data['countryName']);
            unset($data['nationalityName']);
            unset($data['genderName']);

            if (isset($data['responsibleCheckinPartnerId'])) {
                $data['responsibleCheckinPartnerId'] = intval($data['responsibleCheckinPartnerId']);
            }

          //  $data['documentCountryId'] =  intval($data['documentCountryId']); // campo nuevo en el formulario
           // $data['documentType'] = intval($data['documentType']);


            //$data['nationality'] = intval($data['nationality']);
            if (isset($data['countryState'])) {
               // $data['countryState'] = intval($data['countryState']);
            }
            //$data['countryId'] = intval($data['countryId']);

            $url_patch = 'reservations/p/' . $reservation_id . '/checkin-partners/' . $checkin_partner_id . '?pmsPropertyId=' . $this->api_pms_hotel_id;

            $res = $this->callApi('patch',$url_patch, $data);
            Log::info($res);
            if (isset($res['error'])) {
                return $res;
            }
        } else {
            Log::info('res actionOnBoard');
            $data = [];
            $data['actionOnBoard'] = 'true';
            $res = $this->callApi('patch', 'reservations/p/' . $reservation_id . '/checkin-partners/' . $checkin_partner_id . '?pmsPropertyId=' . $this->api_pms_hotel_id, $data);
            Log::info($res);
            if (isset($res['error'])) {
                Log::info('Error onboard');
                return $res;
            }

            $checkin = CheckIn::where('pms', $this->api_pms)
                ->where('remote_id', $checkin_partner_id)
                ->first();
            $checkin->checkin_partner_state = 'onboard';
            if (empty($checkin->number)) {
                $establecimiento_id = $checkin->reservation->folio->establecimiento_id;

                $lastCheckinNumber = DB::table('check_in')
                    ->join('reservations', 'check_in.reservation_id', '=', 'reservations.id')
                    ->join('folios', 'reservations.folio_id', '=', 'folios.id')
                    ->where('folios.establecimiento_id', $establecimiento_id)
                    ->where('check_in.checkin_partner_state', 'onboard')
                    ->whereNotIn('check_in.id', [$checkin->id])
                    ->count();
                $lastCheckinNumber = $lastCheckinNumber + 1;
                $checkin->number = date('Y') . '/' . str_pad($lastCheckinNumber, 7, '0', STR_PAD_LEFT);
            }
            $checkin->save();
        }

        return $res;
    }


    public function documentTypes()
    {
        $res = $this->callApi('get', 'id-categories/');
        return $res;
    }

    public function countries()
    {
        $value = Cache::remember('countries', 900, function () {
            return $this->callApi('get', 'countries/');
        });

        return $value;
    }

    public function country_states($id)
    {
        $value = Cache::remember('countries_states_' . $id, 900, function () use ($id) {
            return $this->callApi('get', 'countries/' . $id . '/country-states');
        });
        return $value;
    }

    public function accountJournals()
    {

        return $this->callApi('get', 'account-journals/?pmsPropertyId=' . $this->api_pms_hotel_id);
    }

    public function transactions($data)
    {
        //Log::info('transaction');
        //Log::info($data);

        return $this->callApi('post', 'folios/' . $data['folioId'] . '/charge', $data);
    }


    public function genders()
    {
        return [
            'male' => 'Masculino',
            'female' => 'Femenino',
            'other' => 'Otro',
        ];
    }

    public function rooms($data = [])
    {
        return $this->callApi('get', 'rooms?pmsPropertyId=' . $this->api_pms_hotel_id);
    }

    public function roomTypes($data = [])
    {
        return $this->callApi('get', 'room-types/?pmsPropertyIds[0]=' . $this->api_pms_hotel_id);
    }


    public function prices($data = [])
    {
        $room_type_id = $data['room_type_id'];
        $from = $data['from'];
        $to = $data['to'];


        $res = $this->callApi('get', 'prices?pmsPropertyId=' . $this->api_pms_hotel_id .
            '&roomTypeId=' . $room_type_id . '&pricelistId=' . $this->price_list_id . '&dateFrom=' . $from . '&dateTo=' . $to);
        if (empty($res)) {
            return [];
        }
        foreach ($res as $k => $row) {
            $date_part = explode("T", $row['date']);
            $res[$k]['date'] = $date_part[0];
        }
        return $res;
    }

    public function availsByRoomType($data = [])
    {
        if (isset($data['daterange'])) {
            $dates = $data['daterange'];
            $date_explode = explode("-", $dates);
            $from = DateTime::createFromFormat('d/m/Y', trim($date_explode[0]))->format('Y-m-d');
            $to = DateTime::createFromFormat('d/m/Y', trim($date_explode[1]));
        } else {
            // cuando se agregar una habitacion a un reserva existente
            $from = DateTime::createFromFormat('Y-m-d', trim($data['checkin']))->format('Y-m-d');
            $to = DateTime::createFromFormat('Y-m-d', trim($data['checkout']));
        }

        $to->modify('-1 day');
        $to = $to->format('Y-m-d');

        $room_types = collect($this->roomTypes());

        $rooms =  collect($this->rooms());
        $filter_type = '';
        if (!empty($data['type_id'])) {
            $filter_type = '&roomTypeId=' . $data['type_id'];
        }

        $avails = $this->callApi('get', 'avails?pmsPropertyId=' . $this->api_pms_hotel_id . '&availabilityFrom=' . $from . '&availabilityTo=' . $to . $filter_type);

        $res = [
            'days' => [],
            'room_types_avails' => []
        ];

        $room_types_avails = [];

        $days = [];
        $price_room_type = [];


        foreach ($avails as $row) {
            $date_part = explode("T", $row['date']);
            $date = $date_part[0];
            $days[] = $date;

            $room_types_avails_day = [];
            foreach ($row['roomIds'] as $room_id) {
                $room =  $rooms->firstWhere('id', $room_id);
                if (!isset($room_types_avails_day[$room['roomTypeId']])) {

                    if (!isset($price_room_type[$room['roomTypeId']])) {
                        $price_room_type[$room['roomTypeId']] = collect($this->prices([
                            'room_type_id' => $room['roomTypeId'],
                            'from' => $from,
                            'to' => $to
                        ]));
                    }

                    $type = $room_types->firstWhere('id', $room['roomTypeId']);

                    $price_day = $price_room_type[$room['roomTypeId']]->firstWhere('date', $date);

                    $room_types_avails_day[$room['roomTypeId']] = [
                        'id' => $room['roomTypeId'],
                        'name' => $type['name'],
                        'prices' => $price_day['price'],
                        'avails' => 1
                    ];
                    if (!isset($room_types_avails[$room['roomTypeId']])) {
                        $room_types_avails[$room['roomTypeId']] = [
                            'id' => $room['roomTypeId'],
                            'name' => $type['name']
                        ];
                    }
                } else {
                    $room_types_avails_day[$room['roomTypeId']]['avails'] = $room_types_avails_day[$room['roomTypeId']]['avails'] + 1;
                }
            }

            $res['days'][] = [
                'date' => $date,
                'date2' => Carbon::parse($date)->format('d M Y'),
                'room_types' => $room_types_avails_day
            ];
        }
        $days_data = collect($res['days']);

        $room_types_avails = array_values($room_types_avails);

        usort($room_types_avails, function ($a, $b) {
            return $a['id'] <=> $b['id'];
        });

        foreach ($room_types_avails as $k => $room_type) {
            $room_types_avails[$k]['quantity'] = 0;
            $room_types_avails[$k]['total'] = 0;

            foreach ($days as $day) {
                $day_row = $days_data->firstWhere('date', $day);
                $day_row_types = collect($day_row['room_types']);

                $type_row = $day_row_types->firstWhere('id', $room_type['id']);
                if ($type_row) {
                    $room_types_avails[$k]['days'][$day] = [
                        'date' => $day,
                        'price' => $type_row['prices'],
                        'avails' => $type_row['avails']
                    ];
                    $room_types_avails[$k]['total'] += $type_row['prices'];
                } else {
                    $room_types_avails[$k]['days'][$day] = [
                        'date' => $day,
                        'price' => '0',
                        'avails' => '0'
                    ];
                }
            }
        }

        $res['room_types_avails'] = $room_types_avails;
        return $res;
    }

    public function folioCreate($data)
    {

        $rooms = [];
        foreach ($data['rooms'] as $room) {
            $rooms[] = [
                'checkin' => $room['checkin'],
                'checkout' => $room['checkout'],
                'roomTypeId' => intval($room['type_id']),
                'adults' => intval($room['adults']),
                'children' => intval($room['children']),
                'partnerRequests' => $room['notes'],
            ];
        }

        $data_folio = [
            'pricelistId' => $this->price_list_id,
            'partnerName' => $data['client_info']['name'],
            'partnerEmail' => $data['client_info']['email'],
            'partnerPhone' => $data['client_info']['mobile'],
            'pmsPropertyId' => $this->api_pms_hotel_id,
            'reservations' => $rooms,
        ];

        $res = $this->callApi('post', 'folios', $data_folio);

        if (isset($res['error'])) {
            return $res;
        }
        return $res;
    }

    public function folioUpdate($data)
    {
        $folio_id = $data['id'];
        unset($data['id']);
        $res = $this->callApi('patch', 'folios/p/' . $folio_id, $data);

        return $res;
    }

    public function folioAddReservation($data)
    {

        $folio_id = $data['folio']['id'];

        unset($data['id']);

        $rooms = [];
        foreach ($data['rooms'] as $room) {
            $rooms[] = [
                'checkin' => $room['checkin'],
                'checkout' => $room['checkout'],
                'roomTypeId' => intval($room['type_id']),
                'adults' => intval($room['adults']),
                'children' => intval($room['children']),
                'partnerRequests' => $room['notes'],
                'pricelistId' => $this->price_list_id,
                'stateCode' => 'draft',
                'toAssign' => false
            ];
        }
        $data_folio = [
            'pmsPropertyId' => $this->api_pms_hotel_id,
            'pricelistId' => $this->price_list_id,
            'reservations' => $rooms,
        ];
        //dd($data_folio);

        $res = $this->callApi('patch', 'folios/p/' . $folio_id, $data_folio);

        return $res;
    }


    public function reservationUpdate($data)
    {
        $reservation_id = $data['id'];
        unset($data['id']);
        $data['pricelistId'] = $this->price_list_id;

        //$data['stateCode'] = 'draft';
        //$data['toAssign'] = 'false';
        if (empty($data['children'])) {
            $data['children'] = 0;
        }

        //'stateCode' => 'draft',
        //      'toAssign' => false

        $res = $this->callApi('patch', 'reservations/p/' . $reservation_id, $data);
        return $res;
    }

    public function getClientInfo($data)
    {

        $partner = $this->callApi('get', 'partners/' . $data['document_type'] . '/' . $data['document_number']);
        if (isset($partner[0]['partnerId'])) {
            return $partner[0];
        }
        return false;
    }

    public function checkinPartnerSave($partner, $reservation)
    {

        $folio_id_remote = $reservation['folioId'];

        $folio_remote = $this->folioDetail($folio_id_remote);

        $folio = Folio::updateOrCreate(
            [
                'remote_id' => $folio_id_remote,
                'establecimiento_id' => $reservation['establecimiento_id']
            ],
            [
                'partner_name' => $folio_remote['partnerName'],
                'partner_phone' => $folio_remote['partnerPhone'],
                'partner_email' => $folio_remote['partnerEmail'],
                'amount_total' => $folio_remote['amountTotal'],
                'state' => $folio_remote['state'],
                'reservation_type' => $folio_remote['reservationType'] ?? '',
                'pending_amount' => $folio_remote['pendingAmount'],
                'first_checkin' => $folio_remote['firstCheckin'] ?? '',
                'last_checkout' => $folio_remote['lastCheckout'] ?? '',
                // 'created_by' => $folio_remote['createdBy'],
                'pricelist_id' => $folio_remote['pricelistId'] ?? null,
                'sale_channel_id' => $folio_remote['saleChannelId'],
                'internal_comment' => $folio_remote['internalComment'],
                'language' => $folio_remote['language']
            ]
        );

        $reservation2 = Reservation::updateOrCreate(
            ['remote_id' => $reservation['id']],
            [
                'name' => $reservation['name'],
                'folio_id' => $folio->id,
                'partner_name' => $reservation['partnerName'],
                'nights' => $reservation['nights'],
                'children' => $reservation['children'],
                'checkin' => $reservation['checkin3'],
                'checkout' => $reservation['checkout3'],
                'arrival_hour' => $reservation['arrivalHour'],
                'departure_hour' => $reservation['departureHour'],
                'adults' => $reservation['adults'],
                'room_name' => $reservation['roomName'],
                'type' => $reservation['reservationType'],
                'price_total' => $reservation['priceTotal'],
                'price_tax' =>  $reservation['priceTax'],
                'services' => json_encode($reservation['services']),
                'reservation_type' => $reservation['reservationType'],
                'created_by' => $reservation['createdBy'],
                'state' => $reservation['stateCode'],
                'create_date' => date('Y-m-d H:i:s'),
                'pms' => $this->api_pms
            ]
        );
        $partner['reservation_id'] = $reservation2->remote_id;
        $expeditionDate = $partner['documentExpeditionDate'] ?? '1999-01-01';


        $checkin_data =  [
            'reservation_id' => $reservation2->id,
            'firstname' => $partner['firstname'],
            'lastname' => $partner['lastname'],
            'lastname2' => $partner['lastname2'],
            'email' => $partner['email'],
            'mobile' => $partner['mobile'],
            'document_type' => $partner['documentType'],
            'document_number' => $partner['documentNumber'],
            'document_expedition_date' => $expeditionDate,
            'document_country_id' => $partner['documentCountryId'],
            'document_support_number' => $partner['documentSupportNumber'],
            'gender' => $partner['gender'],
            'birthdate' => $partner['birthdate'],
            'residence_street' => $partner['residenceStreet'],
            'zip' => $partner['zip'],
            'residence_city' => $partner['residenceCity'],
            'nationality' => $partner['nationality'],
            'country_state' => $partner['countryState'] ?? '',
            'country_id' => $partner['countryId'],
            'document_type_name' => $partner['documentTypeName'],
            'nationality_name' => $partner['nationalityName'],
            'country_name' => $partner['countryName'],
            'country_state_name' => $partner['countryStateName'] ?? '',
            'responsible_checkin_partner_id' => $partner['responsibleCheckinPartnerId'] ?? '',
            'relationship' => $partner['relationship'] ?? '',
            'pms' => $this->api_pms
        ];


        CheckIn::updateOrCreate(
            ['remote_id' => $partner['id']],
            $checkin_data
        );

        if(isset($partner['documentExpeditionDate'])){
            if (!empty($partner['documentExpeditionDate'])) {
                $fechaCarbon = Carbon::createFromFormat('Y-m-d', $partner['documentExpeditionDate']);
                $partner['documentExpeditionDate'] = $fechaCarbon->format('d/m/Y');
            }
        }else{
            $expeditionDate = $partner['documentExpeditionDate'] ?? '1999-01-01';
            $fechaCarbon = Carbon::createFromFormat('Y-m-d', $expeditionDate);
            $partner['documentExpeditionDate'] = $fechaCarbon->format('d/m/Y');

        }

        if (!empty($partner['birthdate'])) {
            $fechaCarbon = Carbon::createFromFormat('Y-m-d', $partner['birthdate']);
            $partner['birthdate'] = $fechaCarbon->format('d/m/Y');
        }

        $partner['reservation_remote_id'] = $reservation2->remote_id;

        if (isset($partner['action']) && $partner['action'] == 'checkin_save') {
            Log::info('board');
            $checkin_partner = $this->reservationCheckinPartnersSave($partner);
            $checkin_partner = $this->reservationCheckinPartnersSave($partner, true);
        } else {
            Log::info('no board');
            $checkin_partner = $this->reservationCheckinPartnersSave($partner);
        }

        return $checkin_partner;
    }

    public function faltaFirma($id){
        return true;
    }
}
