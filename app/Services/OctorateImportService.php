<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;

use Carbon\Carbon;

class OctorateImportService
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
            if ($key == 0) {
                continue;
            }
            $hotel_api = new HotelApiService($this->hotel_id);
            //Log::info($row);
            $reserva_data = [
                'client_info' => [
                    'name' => trim($row[2]),
                    'email' => trim($row[21]),
                    'mobile' => trim($row[25]),
                ],
                'rooms' => [
                    [
                        'type' => [],
                        'type_id' => 1,
                        'checkin' => Carbon::createFromFormat('d/m/Y', $row[0])->format('Y-m-d'),
                        'checkout' => Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'),
                        'adults' => $row[47],
                        'children' => $row[48],
                        'notes' => $row[8] . ' ' .$row[16] . ' ' . $row[13],
                        'total' =>  $row[7],
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
