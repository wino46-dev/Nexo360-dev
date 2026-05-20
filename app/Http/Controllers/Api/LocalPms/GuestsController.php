<?php

namespace App\Http\Controllers\Api\LocalPms;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Illuminate\Http\Request;

class GuestsController extends Controller
{
    public function index(Request $request, string $hotel_code, string $booking_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            // Local service uses reservationCheckinPartners by reservation id; we assume booking_id is folio id and client passes reservation_id via query when needed
            $reservationId = $request->query('reservation_id', $booking_id);
            $res = $service->reservationCheckinPartners($reservationId);
            return response()->json($res);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function upsert(Request $request, string $hotel_code, string $booking_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $payload = $request->all();
            // Ensure reservation_id is present
            if (empty($payload['reservation_id'])) {
                return response()->json([
                    'code' => 'validation_error',
                    'message' => 'reservation_id is required',
                ], 422);
            }
            $res = $service->checkinPartnerSave($payload, ['id' => $payload['reservation_id']]);
            return response()->json($res, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function board(Request $request, string $hotel_code, string $booking_id, int $guest_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $payload = [
                'id' => $guest_id,
            ];
            $res = $service->reservationCheckinPartnersSave($payload, true);
            return response()->json($res);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
