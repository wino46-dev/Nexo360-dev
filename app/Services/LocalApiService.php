<?php

namespace App\Services;

use App\Models\Avail;
use App\Models\CheckIn;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Establecimiento;
use Exception;

use App\Models\ControlError;
use App\Models\Folio;
use App\Models\Reserva;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use DateInterval;
use DatePeriod;
use DateTime;

use App\Services\Contracts\HotelPmsInterface;

class LocalApiService implements HotelPmsInterface
{
    public $hotel_id;

    public $room_types_list = null;
    public $price_list_id = null;

    public function login()
    {
        return true;
    }

    public function resetAuthCache(): void
    {
        // Local provider has no external auth; nothing to reset
    }
    public $api_pms = null;


    public function __construct($hotel)
    {
        Log::info('cont LocalApiService');
        $this->hotel_id = $hotel->id;
        $this->price_list_id = 3;
        $this->api_pms = $hotel->api_pms;
    }


    public function folioSearch($data)
    {
        $query = Folio::where('establecimiento_id', $this->hotel_id);

        if (!empty($data['date_start']) && !empty($data['date_end'])) {
            $query->whereBetween('first_checkin', [$data['date_start'], $data['date_end']]);
        }
        if (!empty($data['q'])) {
            $query->where('partner_name', 'like', '%' . $data['q'] . '%');
        }
        $query->orderBy('first_checkin', 'desc');

        $folios = $query->get()->toArray();

        $res = [];
        foreach ($folios as &$row) {

            $firstCheckin2 = Carbon::parse($row['first_checkin'])->format('d M Y');
            $lastCheckout2 = Carbon::parse($row['last_checkout'])->format('d M Y');

            $firstCheckin3 = Carbon::parse($row['first_checkin'])->format('Y-m-d');
            $lastCheckout3 = Carbon::parse($row['last_checkout'])->format('Y-m-d');

            $room_count = Reservation::where('folio_id', $row['id'])->count();

            $partners_count = 0;
            $partners_onboard = 0;

            $reservations = Reservation::where('folio_id', $row['id'])
                ->get();
            foreach($reservations as $reservation){
                $partners_count += $reservation->adults ?? 0;
                $partners_count += $reservation->children ?? 0;
                $checkins = CheckIn::where('pms', $this->api_pms)
                    ->where('reservation_id', $reservation->id)
                    ->get();
                foreach($checkins as $checkin){
                    if($checkin->checkin_partner_state == 'onboard'){
                        $partners_onboard += 1;
                    }
                }
            }


            $res[] = [
                'id' => $row['id'],
                'name' => $row['remote_id'] ?? '',
                'partnerName' => $row['partner_name'],
                'partnerEmail' => $row['partner_email'],
                'partnerPhone' => $row['partner_phone'],
                'reservations_count' => $room_count,
                'firstCheckin' => $row['first_checkin'],
                'lastCheckout' => $row['last_checkout'],
                'firstCheckin2' => $firstCheckin2,
                'lastCheckout2' => $lastCheckout2,
                'firstCheckin3' => $firstCheckin3,
                'lastCheckout3' => $lastCheckout3,
                'state' => $row['state'],
                'amountTotal' => $row['amount_total'],
                'pendingAmount' => $row['pending_amount'],
                'hotel_id' => $this->hotel_id,
                'partners_count' => $partners_count,
                'partners_onboard' => $partners_onboard,

            ];
        }

        $res = collect($res)
            ->sortBy('first_checkin');

        return $res->values()->all();
    }

    public function folioDetail($id)
    {
        $res = Folio::find($id)->toArray();

        $res['reservations'] = $this->folioReservations($id);
        $res['name'] = $res['id'];
        $res['partnerName'] = $res['partner_name'];
        $res['partnerPhone'] = $res['partner_phone'];
        $res['partnerEmail'] = $res['partner_email'];
        $res['amountTotal'] = $res['amount_total'];
        $res['pendingAmount'] = $res['pending_amount'];

        $res['firstCheckin2'] = Carbon::parse($res['first_checkin'])->format('d M Y');
        $res['lastCheckout2'] = Carbon::parse($res['last_checkout'])->format('d M Y');

        $res['firstCheckin3'] = Carbon::parse($res['first_checkin'])->format('Y-m-d');
        $res['lastCheckout3'] = Carbon::parse($res['last_checkout'])->format('Y-m-d');
        $res['hotel_id'] = $this->hotel_id;

        return $res;
    }

    public static function calculateNights($startDate, $endDate)
    {
        // Convertir las fechas a instancias de Carbon
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Verificar que la fecha de inicio no sea mayor que la fecha de fin
        if ($start->gt($end)) {
            return null;
        }

        // Calcular la diferencia en días
        return $start->diffInDays($end);
    }

    public function folioReservations($id)
    {
        $res = Reservation::where('folio_id', $id)->get()->toArray();

        foreach ($res as $k => &$row) {
            $res[$k]['name'] = $row['id'];
            $res[$k]['checkin2'] = Carbon::parse($row['checkin'])->format('d M Y');
            $res[$k]['checkout2'] = Carbon::parse($row['checkout'])->format('d M Y');
            $res[$k]['checkin3'] = Carbon::parse($row['checkin'])->format('Y-m-d');
            $res[$k]['checkout3'] = Carbon::parse($row['checkout'])->format('Y-m-d');
            $res[$k]['stateCode'] = $row['state'];
            $res[$k]['numServices'] = 0;
            $res[$k]['priceTotal'] = $row['price_total'];

            $res[$k]['nights'] = self::calculateNights($row['checkin'], $row['checkout']);


            $res[$k]['roomTypeName'] = 'no room type';

            //$lines = $this->reservationDetailLines($row['id']);
            $lines = [];

            if (!empty($lines[0]['roomId'])) {
                $room = $this->room($lines[0]['roomId']);
                $res[$k]['roomId'] = $room['id'];
                $res[$k]['roomName'] = $room['name'];
            } else {
                $res[$k]['roomId'] = null;
                $res[$k]['roomName'] = $row['room_name'] ?? null;
            }
            $res[$k]['services'] = [];
            $res[$k]['hotel_id'] = $this->hotel_id;
        }
        return $res;
    }

    public function reservationDetail($id)
    {
        $res = Reservation::find($id);
        if (!$res) {
            return null;
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
            $res['roomName'] = $res['room_name'] ?? null;
        }

        $res['services'] = $this->reservationServices($id);
        $res['folioId'] = $res['folio_id'];

        return $res;
    }

    public function reservationDetailLines($id)
    {
        //$res = $this->callApi('get', 'reservations' . '/' . $id . '/reservation-lines');
        $res = [];

        if (isset($res['error'])) {
            return $res;
        }

        return $res;
    }

    public function reservationServices($id)
    {
        $res = [];
        if (isset($res['error'])) {
            return $res;
        }
        return $res;
    }

    public function room($id)
    {
        $value = Cache::remember('room_' . $id, 900, function () use ($id) {
            $res = ['success' => 'ok'];
            if (isset($res['error'])) {
                return $res;
            }
            return $res;
        });
        return $value;
    }

    public function reservationCheckinPartners($id)
    {
        $checkins = CheckIn::where('pms', $this->api_pms)
            ->where('reservation_id', $id)
            ->get();
        $checkin_list = [];
        if ($checkins) {
            foreach ($checkins as $row) {
                $checkin_list[] = [
                    'id' => $row['id'],
                    'partnerId' => NULL,
                    'reservationId' => $row['reservation_id'],
                    'name' => $row['name'],
                    'firstname' => $row['firstname'],
                    'lastname' => $row['lastname'],
                    'lastname2' => $row['lastname2'],
                    'email' => $row['email'],
                    'mobile' => $row['mobile'],
                    'relationship' => $row['relationship'],
                    'responsibleCheckinPartnerId' => $row['responsible_checkin_partner_id'],
                    'documentType' => $row['document_type'],
                    'documentNumber' => $row['document_number'],
                    'documentExpeditionDate' => $row['document_expedition_date'],
                    'documentSupportNumber' => $row['document_support_number'],
                    'documentCountryId' => $row['document_country_id'],
                    'gender' => $row['gender'],
                    'birthdate' => $row['birthdate'],
                    'residenceStreet' => $row['residence_street'],
                    'zip' => $row['zip'],
                    'residenceCity' => $row['residence_city'],
                    'nationality' => $row['nationality'],
                    'countryState' => $row['country_name'],
                    'countryStateName' => $row['country_state_name'],
                    'countryId' => $row['country_id'],
                    'checkinPartnerState' => $row['checkin_partner_state'],
                    'signature' => $row['signature'],
                ];
            }
        }
        Log::info($checkin_list);

        return $checkin_list;
    }

    public function reservationCheckinPartnersSave($data, $onBoard = false)
    {
        //$reservation_id = $data['reservation_id'];
        $checkin_partner_id = $data['id'];
        //Log::info($data);
        if ($onBoard) {
            $checkin = CheckIn::find($checkin_partner_id);
            if (!$checkin) {
                return ['error' => 'Checkin not found'];
            }
            $checkin->checkin_partner_state = 'onboard';
            $checkin->save();
            return $checkin->id;
        }

        return false;
    }


    public function documentTypes()
    {
        $res = UtilService::documents_list();
        $list_new = [];
        foreach ($res as $key => $row) {
            $list_new[] = [
                'documentType' => $row,
                'id' => $key
            ];
        }
        return $list_new;
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
        $value = [];
        return $value;
    }

    public function accountJournals()
    {
        return [];
    }

    public function transactions($data)
    {
        return true;
    }

    public function genders()
    {
        return [
            'male' => 'Masculino',
            'female' => 'Femenino',
            'other' => 'Otro',
        ];
    }

    public function roomTypes($data = [])
    {
        $res = RoomType::where('establecimiento_id', $this->hotel_id)
            ->get()->toArray();
        return $res;
    }

    public function rooms($data = [])
    {
        $res = Room::where('establecimiento_id', $this->hotel_id)
            ->get()->toArray();
        return $res;
    }

    public function prices($data = [])
    {
        $room_type_id = $data['room_type_id'];
        $from = $data['from'];
        $to = $data['to'];

        $res = RoomTypePrice::where('establecimiento_id', $this->hotel_id)
            ->whereBetween('date', [$from, $to])
            ->where('room_type_id', $room_type_id)
            ->get();

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
            $from = DateTime::createFromFormat('Y-m-d', trim($data['checkin']))->format('Y-m-d');
            $to = DateTime::createFromFormat('Y-m-d', trim($data['checkout']));
        }

        $to->modify('-1 day');
        $to = $to->format('Y-m-d');

        $room_types = collect($this->roomTypes());

        $rooms =  collect($this->rooms());

        $avails = $this->avails([
            'from' => $from,
            'to' => $to
        ]);

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
            foreach ($row['room_ids'] as $room_id) {
                $room =  $rooms->firstWhere('id', $room_id);

                if (!isset($room_types_avails_day[$room['room_type_id']])) {

                    if (!isset($price_room_type[$room['room_type_id']])) {
                        $price_room_type[$room['room_type_id']] = collect($this->prices([
                            'room_type_id' => $room['room_type_id'],
                            'from' => $from,
                            'to' => $to
                        ]));
                    }

                    $type = $room_types->firstWhere('id', $room['room_type_id']);

                    $price_day = $price_room_type[$room['room_type_id']]->firstWhere('date', $date);

                    $room_types_avails_day[$room['room_type_id']] = [
                        'id' => $room['room_type_id'],
                        'name' => $type['name'],
                        'prices' => $price_day['price'] ?? 0,
                        'avails' => 1
                    ];
                    if (!isset($room_types_avails[$room['room_type_id']])) {
                        $room_types_avails[$room['room_type_id']] = [
                            'id' => $room['room_type_id'],
                            'name' => $type['name']
                        ];
                    }
                } else {
                    $room_types_avails_day[$room['room_type_id']]['avails'] = $room_types_avails_day[$room['room_type_id']]['avails'] + 1;
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

    public function avails($filter = [])
    {

        $startDate = new DateTime($filter['from']);
        $endDate = new DateTime($filter['to']);
        $endDate->modify('+1 day');
        $dateInterval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $dateInterval, $endDate);
        $res = [];
        foreach ($dateRange as $date) {
            $fecha = $date->format('Y-m-d');
            $room_ids = Avail::where('establecimiento_id', $this->hotel_id)
                ->where('date', $fecha)
                ->where('available', 1)
                ->get()->pluck('room_id')->toArray();

            $res[] = [
                'date' => $fecha,
                'room_ids' => $room_ids
            ];
        }
        return $res;
    }

    public function checkinPartnersCreate($reservation)
    {

        $total = intval($reservation->adults) + intval($reservation->children);

        $total_actual = CheckIn::where('pms', $this->api_pms)
            ->where('reservation_id', $reservation->id)
            ->count();
        $total_crear = intval($total) - intval($total_actual);

        if ($total_crear > 0) {
            for ($i = 1; $i <= $total_crear; $i++) {
                CheckIn::create([
                    'pms' => $this->api_pms,
                    'reservation_id' => $reservation->id
                ]);
            }
        }
    }

    public function folioCreate($data)
    {

        $folio = null;
        if (!empty($data['remote_id'])) {
            $folio = Folio::where('establecimiento_id', $this->hotel_id)
                ->where('remote_id', $data['remote_id'])
                ->first();
        }

        if ($folio) {
            $folio->update([
                'partner_name' => $data['client_info']['name'],
                'partner_email' => $data['client_info']['email'],
                'partner_phone' => $data['client_info']['mobile'],
                'state' => 'pending',
                'internal_comment' => $data['source'] ?? $folio->internal_comment
            ]);
            // Limpiar reservas existentes para recrearlas con la nueva info
            $existingReservations = Reservation::where('folio_id', $folio->id)->get();
            foreach ($existingReservations as $oldRes) {
                CheckIn::where('reservation_id', $oldRes->id)->delete();
                $oldRes->delete();
            }
        } else {
            $folio = Folio::create([
                'establecimiento_id' => $this->hotel_id,
                'pricelist_id' => $this->price_list_id,
                'partner_name' => $data['client_info']['name'],
                'partner_email' => $data['client_info']['email'],
                'partner_phone' => $data['client_info']['mobile'],
                'remote_id' => $data['remote_id'] ?? '',
                'state' => 'pending',
                'internal_comment' => $data['source'] ?? ''
            ]);
        }

        $checkin_list = [];
        $checkout_list = [];
        $folio_total = 0;

        $created_by = auth()->user()->id ?? 0;
        if(!empty($data['created_by'])){
            $created_by = $data['created_by'];
        }


        foreach ($data['rooms'] as $room) {
            $reservation = Reservation::create([
                'folio_id' => $folio->id,
                'partner_name' => $data['client_info']['name'],
                'partner_email' => $data['client_info']['email'],
                'partner_phone' => $data['client_info']['mobile'],
                'checkin' => $room['checkin'],
                'checkout' => $room['checkout'],
                'room_type_id' => intval($room['type_id']),
                'adults' => intval($room['adults']),
                'children' => intval($room['children']),
                'reservation_type' => 1,
                'price_total' => intval($room['total']),
                'price_tax' => 0,
                'state' => 'pending',
                'notes' => $room['notes'],
                'created_by' => $data['source'] ?? $created_by,
                'room_name' => $room['room_name'] ?? '',
                'pms' => $this->api_pms,
                'remote_id' => $data['remote_id'] ?? ''
            ]);

            $this->checkinPartnersCreate($reservation);

            $checkin_list[] = $room['checkin'];
            $checkout_list[] = $room['checkout'];
            $folio_total += intval($room['total']);
        }

        $folio_model = Folio::find($folio->id);
        if ($folio_model) {
            $folio_model->first_checkin = min($checkin_list);
            $folio_model->last_checkout = max($checkout_list);

            $folio_model->amount_total = $folio_total;
            $folio_model->pending_amount = $folio_total;
            if (empty($folio_model->created_by)) {
                $folio_model->created_by = auth()->id() ?? $created_by;
            }
            if (empty($folio_model->created_by)) {
                $folio_model->created_by = 0;
            }
            $folio_model->save();
        }
        $res = $folio->id;


        if (isset($res['error'])) {
            return $res;
        }
        return $res;
    }

    public function folioUpdate($data)
    {
        $folio_id = $data['id'];

        $folio_model = Folio::find($folio_id);
        if ($folio_model) {
            $folio_model->partner_name = $data['partnerName'];
            $folio_model->partner_phone = $data['partnerPhone'];
            $folio_model->partner_email = $data['partnerEmail'];

            $folio_model->save();
            return $folio_id;
        }

        return null;
    }

    public function folioAddReservation($data)
    {

        $folio_id = $data['folio']['id'];

        unset($data['id']);

        $rooms = [];
        foreach ($data['rooms'] as $room) {

            $reservation = Reservation::create([
                'folio_id' => $folio_id,
                'checkin' => $room['checkin'],
                'checkout' => $room['checkout'],
                'room_type_id' => intval($room['type_id']),
                'adults' => intval($room['adults']),
                'children' => intval($room['children']),
                'reservation_type' => 1,
                'price_total' => intval($room['type']['total']),
                'price_tax' => 0,
                'state' => 'pending',
                'notes' => $room['notes'],
                'created_by' => auth()->user()->id,
                'pms' => $this->api_pms
            ]);
        }

        return 'ok';
    }

    public function reservationUpdate($data)
    {
        $reservation_id = $data['id'];

        $reservation = Reservation::find($reservation_id);
        if ($reservation) {
            $reservation->checkin = $data['checkin'];
            $reservation->checkout = $data['checkout'];
            $reservation->adults = $data['adults'];
            $reservation->children = $data['children'];
            $reservation->notes = $data['notes'];
            $reservation->save();
        }

        return null;
    }

    public function faltaFirma($checkin_id)
    {
        $checkin = CheckIn::find($checkin_id);
        if ($checkin) {
            $checkin->checkin_partner_state = '';
            $checkin->save();
            return true;
        }
        return null;
    }

    public function getReservationsStatus($data)
    {
        return [];
    }

    public function getClientInfo($data)
    {
        dd($data);
    }

    public function checkinPartnerSave($partner, $reservation)
    {
        $checkin_data =  [
            'reservation_id' => $partner['reservation_id'],
            'firstname' => $partner['firstname'],
            'lastname' => $partner['lastname'],
            'lastname2' => $partner['lastname2'],
            'email' => $partner['email'],
            'mobile' => $partner['mobile'],
            'document_type' => $partner['documentType'],
            'document_number' => $partner['documentNumber'],
            'document_expedition_date' => $partner['documentExpeditionDate'],
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


        $checkin_data['remote_id'] = $partner['id'];
        CheckIn::updateOrCreate(
            ['id' => $partner['id']],
            $checkin_data
        );


        if (isset($partner['action']) && $partner['action'] == 'checkin_save') {
            Log::info('board');
            $checkin_partner = $this->reservationCheckinPartnersSave($partner, true);
        } else {
            Log::info('no board');
            $checkin_partner = $this->reservationCheckinPartnersSave($partner);
        }

        return $checkin_partner;

    }

}
