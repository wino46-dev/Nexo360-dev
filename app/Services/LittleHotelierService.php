<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;

use Carbon\Carbon;

class LittleHotelierService
{

    public $hotel_id;

    public function __construct($hotel_id)
    {

        $this->hotel_id = $hotel_id;
    }

    public function import($data)
    {
        $list_created = [];
        foreach ($data as $key => $row) {
            //Log::info($row);
            if ($key == 0) {
                
                continue;
            }
            $hotel_api = new HotelApiService($this->hotel_id);
            
            $reserva_data = [
                'client_info' => [
                    'name' => trim($row[5]) .' '. trim($row[6]),
                    'email' => trim($row[7]),
                    'mobile' => trim($row[8]),
                ],
                'rooms' => [
                    [
                        'type' => [],
                        'type_id' => 1,
                        'checkin' => $row[16],
                        'checkout' => $row[17],
                        'adults' => $row[38],
                        'children' => $row[39],
                        'notes' => trim($row[21]) . ' ' .trim($row[20]),
                        'total' => $row[36],
                        'hotel_id' => $this->hotel_id,
                    ],
                ],
            ];

            $res = $hotel_api->folioCreate($reserva_data);
            if (isset($res['error'])) {
                return response()->json($res, 500);
            }
            $list_created[] = $res;
        }
        return $list_created;
    }
    
}
