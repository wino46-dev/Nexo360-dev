<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Http\Resources\Admin\ReservaResource;
use App\Models\Reserva;
use App\Services\HotelApiService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->all();

        $hotel_api = new HotelApiService($data['hotel_id']);

        $reserva_data = [
            'client_info' => [
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
            ],
            'rooms' => [
                [
                    'type' => [],
                    'type_id' => 1,
                    'checkin' => $data['checkin'],
                    'checkout' => $data['checkout'],
                    'adults' =>  $data['adults'],
                    'children' =>  $data['children'],
                    'notes' =>  $data['notes'],
                    'total' => '0',
                    'hotel_id' => $data['hotel_id'],
                ],
            ],
        ];
        try {
            $res = $hotel_api->folioCreate($reserva_data);
            return $res;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la reserva',               
            ], 500);
        }
    }
}
