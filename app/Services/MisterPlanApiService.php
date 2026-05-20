<?php

namespace App\Services;

use App\Exceptions\PmsNotSupportedException;
use App\Services\Contracts\HotelPmsInterface;
use App\Services\Http\MisterPlanHttpClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;

use Carbon\Carbon;

class MisterPlanApiService implements HotelPmsInterface
{

    public $hotel_id;
    public $hotel;

    public function resetAuthCache(): void
    {
        // MisterPlan does not use token-based auth here; nothing to reset
    }

    private ?MisterPlanHttpClient $client = null;
    private bool $realApiEnabled = false;

    public function __construct($hotel)
    {
        $this->hotel = $hotel;
        $this->hotel_id = $hotel->id;

        $cfg = Config::get('pms.misterplan', []);
        $this->realApiEnabled = (bool)($cfg['real_api_enabled'] ?? false);

        // Build effective config preferring per-hotel credentials
        $effective = [
            'base_url' => $this->hotel->api_pms_url ?: ($cfg['base_url'] ?? ''),
            'api_key' => $this->hotel->misterplan_api_key ?: ($cfg['api_key'] ?? ''),
            'channel_id' => $this->hotel->misterplan_channel_id ?: ($cfg['channel_id'] ?? 0),
            'timeout' => $cfg['timeout'] ?? 15,
            'retries' => $cfg['retries'] ?? 2,
            'retry_delay_ms' => $cfg['retry_delay_ms'] ?? 300,
        ];

        $apiKey = (string)$effective['api_key'];
        $base = (string)$effective['base_url'];
        if ($this->realApiEnabled && $apiKey !== '' && $base !== '') {
            $this->client = new MisterPlanHttpClient($effective);
        }
    }

    public function login()
    {
        return true;
    }


    public function import($data)
    {
        $list_created = [];
        foreach ($data as $key => $row) {
            if ($key == 0) {
                continue;
            }
            $hotel_api = new LocalApiService($this->hotel);
            //Log::info($row);
            $reserva_data = [
                'client_info' => [
                    'name' => trim($row[7]),
                    'email' => '',
                    'mobile' => '',
                ],
                'rooms' => [
                    [
                        'type' => [],
                        'type_id' => 1,
                        'checkin' => Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d'),
                        'checkout' => Carbon::createFromFormat('d/m/Y', $row[5])->format('Y-m-d'),
                        'adults' => $row[6],
                        'children' => '0',
                        'notes' => $row[10] . ' ' . $row[11] . ' ' . $row[12],
                        'total' => '0',
                        'hotel_id' => $this->hotel_id,
                    ],
                ],
            ];
            //dump($reserva_data);

            $res = $hotel_api->folioCreate($reserva_data);
            if (isset($res['error'])) {
                return response()->json($res, 500);
            }
            $list_created[] = $res;
        }
        return $list_created;
    }

    public function importWebhook($data)
    {
        $hotel_api = new LocalApiService($this->hotel);

        $rooms = [];
        foreach($data['rooms'] as $room){
            $rooms[] = [
                'type' => [],
                'type_id' => 1,
                'checkin' => $room['arrival_date'],
                'checkout' => $room['departure_date'],
                'adults' => $room['occupancy'],
                'children' => '0',
                'notes' => 'Habitación: '. $room['room_name'],
                'total' => $room['price'],
                'hotel_id' => $this->hotel_id,
                'room_name' => $room['room_name'],
            ];

        }
        $reserva_data = [
            'client_info' => [
                'name' => trim($data['customer']['name']),
                'email' => $data['customer']['email'],
                'mobile' => $data['customer']['phone'],
            ],
            'rooms' => $rooms,
            // Ensure integer for DB integer columns; store source label elsewhere if needed
            'created_by' => 0,
            'source' => $data['source'],
            'remote_id' => $data['booking_id']
        ];

        $res = $hotel_api->folioCreate($reserva_data);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function getReservationsStatus($data)
    {
        return [];
    }

    public function folioSearch($data)
    {
        // If real API is enabled and client available, try MisterPlan getBookings
        if ($this->client) {
            try {
                $body = [
                    'hotel_code' => (int)($this->hotel->remote_hotel_id),
                ];
                // Optional filters mapping according to OpenAPI
                if (!empty($data['preCheckin'])) {
                    $body['preCheckin'] = (int)$data['preCheckin'];
                }
                if (!empty($data['arrival_date_at'])) {
                    $body['arrival_date_at'] = $data['arrival_date_at']; // expects ['start_date' => 'YYYY-MM-DD', 'end_date' => 'YYYY-MM-DD']
                }
                if (!empty($data['filter'])) {
                    $body['filter'] = $data['filter']; // { email, booking_id|arrival_date }
                }
                $mp = $this->client->post('/v1/getBookings', $body);
                // Return MisterPlan response as-is for now (non-breaking because Local fallback remains below on error)
                if (is_array($mp)) {
                    return $mp;
                }
            } catch (\Throwable $e) {
                Log::warning('MisterPlan getBookings failed, falling back to Local', [
                    'hotel_id' => $this->hotel_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        // Fallback to Local behavior
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->folioSearch($data);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function folioDetail($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->folioDetail($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function folioReservations($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->folioReservations($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function reservationDetail($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->reservationDetail($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function reservationDetailLines($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->reservationDetailLines($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function reservationServices($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->reservationServices($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function reservationCheckinPartners($id)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->reservationCheckinPartners($id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }
    public function documentTypes()
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->documentTypes();
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function countries()
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->countries();
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function genders()
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->genders();
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function country_states($id)
    {
        $value = [];
        return $value;
    }

    public function accountJournals()
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->accountJournals();
    }

    public function transactions($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->transactions($data);
    }

    public function roomTypes($data = [])
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->roomTypes($data);
    }

    public function rooms($data = [])
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->rooms($data);
    }

    public function availsByRoomType($data = [])
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->availsByRoomType($data);
    }

    public function folioCreate($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->folioCreate($data);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function folioUpdate($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->folioUpdate($data);
    }

    public function folioAddReservation($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->folioAddReservation($data);
    }

    public function reservationUpdate($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->reservationUpdate($data);
    }

    public function checkinPartnerSave($partner, $reservation)
    {
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->checkinPartnerSave($partner, $reservation);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }


    public function reservationCheckinPartnersSave($data, $onBoard = false)
    {
        // Try to push to MisterPlan Checkin API if enabled and we have the minimum fields
        if ($this->client) {
            try {
                $hotelCode = (string)$this->hotel->remote_hotel_id;
                $bookingId = $data['booking_id'] ?? $data['bookingId'] ?? null;

                if ($bookingId) {
                    $guest = [
                        'external_guest_id' => (string)($data['id'] ?? $data['external_guest_id'] ?? uniqid('guest_')),
                        'state' => $data['state'] ?? 'new',
                        'name' => $data['firstname'] ?? $data['name'] ?? '',
                        'surname' => $data['lastname'] ?? '',
                        'second_surname' => $data['lastname2'] ?? '',
                        'gender' => $this->mapGender($data['gender'] ?? null),
                        'birthdate' => $data['birthdate'] ?? null,
                        'nationality' => $this->mapNationality($data['nationality'] ?? ''),
                        'country' => $data['countryName'] ?? $data['country_name'] ?? '',
                        'email' => $data['email'] ?? '',
                        'phone_number' => $data['mobile'] ?? '',
                        'address' => $data['residenceStreet'] ?? '',
                        'city' => $data['residenceCity'] ?? '',
                        'postal_code' => $data['zip'] ?? '',
                        'document' => [[
                            'document_type' => $this->mapDocumentType($data['documentType'] ?? ''),
                            'document_number' => $data['documentNumber'] ?? '',
                            'document_support_number' => $data['documentSupportNumber'] ?? '',
                            'document_date_issue' => $data['documentExpeditionDate'] ?? null,
                        ]],
                    ];

                    $body = [
                        'hotel_code' => $hotelCode,
                        'booking_id' => (string)$bookingId,
                        'guest' => [$guest],
                    ];

                    $this->client->post('/pushCheckin', $body);
                }
            } catch (\Throwable $e) {
                Log::warning('MisterPlan pushCheckin failed, continuing with Local persistence', [
                    'hotel_id' => $this->hotel_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Always persist locally to keep current behavior
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->reservationCheckinPartnersSave($data, $onBoard);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    private function mapGender($value): ?string
    {
        if (!$value) return null;
        $v = strtolower((string)$value);
        if (in_array($v, ['m','male','h','masculino'])) return 'M';
        if (in_array($v, ['f','female','mujer','femenino'])) return 'F';
        return 'O';
    }

    private function mapDocumentType($value): string
    {
        $v = strtoupper((string)$value);
        // Map common labels to MisterPlan enum: DNI, PAS, OTRO
        if (strpos($v, 'PAS') !== false) return 'PAS';
        if (strpos($v, 'DNI') !== false || strpos($v, 'NIE') !== false || strpos($v, 'ID') !== false) return 'DNI';
        return 'OTRO';
    }

    private function mapNationality($value): string
    {
        $v = strtoupper((string)$value);
        // If already 2-letter ISO code, return as is
        if (preg_match('/^[A-Z]{2}$/', $v)) return $v;
        // Fallback: try simple mappings
        $map = [
            'ESPAÑA' => 'ES', 'SPAIN' => 'ES', 'ESP' => 'ES',
            'FRANCE' => 'FR', 'FRANCIA' => 'FR', 'FRA' => 'FR',
            'PORTUGAL' => 'PT', 'PORTUGAL (PT)' => 'PT',
        ];
        return $map[$v] ?? 'ES';
    }
    public function faltaFirma($checkin_id){
        $hotel_api = new LocalApiService($this->hotel);
        $res = $hotel_api->faltaFirma($checkin_id);
        if (isset($res['error'])) {
            return response()->json($res, 500);
        }
        return $res;
    }

    public function getClientInfo($data)
    {
        $hotel_api = new LocalApiService($this->hotel);
        return $hotel_api->getClientInfo($data);
    }

}
