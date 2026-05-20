<?php

namespace App\Http\Controllers\Api\LocalPms;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    public function index(Request $request, string $hotel_code)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $filters = [
                'email' => $request->query('email'),
                'external_locator' => $request->query('external_locator'),
                'arrival_start' => $request->query('arrival_start'),
                'arrival_end' => $request->query('arrival_end'),
                'page' => (int) $request->query('page', 1),
                'per_page' => (int) $request->query('per_page', 25),
            ];
            return response()->json($service->folioSearch($filters));
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request, string $hotel_code)
    {
        $data = $request->all();
        // Map incoming payload to LocalApiService expected structure
        $payload = [
            'client_info' => $data['customer'] ?? [],
            'rooms' => $data['rooms'] ?? [],
            'concepts' => $data['concepts'] ?? [],
        ];
        if (!empty($data['external_locator'])) {
            $payload['external_locator'] = $data['external_locator'];
        }
        if (!empty($data['external_source'])) {
            $payload['external_source'] = $data['external_source'];
        }

        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $res = $service->folioCreate($payload);
            return response()->json($res, 201);
        } catch (\Throwable $e) {
            $code = 500;
            $err = $e->getMessage();
            if (stripos($err, 'duplicate') !== false) {
                $code = 409;
            }
            return response()->json([
                'code' => $code === 409 ? 'conflict' : 'server_error',
                'message' => $err,
            ], $code);
        }
    }

    public function show(Request $request, string $hotel_code, string $booking_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            return response()->json($service->folioDetail($booking_id));
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'not_found',
                'message' => 'Booking not found',
            ], 404);
        }
    }

    public function update(Request $request, string $hotel_code, string $booking_id)
    {
        $data = $request->all();
        $payload = array_merge($data, ['id' => $booking_id]);
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $res = $service->folioUpdate($payload);
            return response()->json($res);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, string $hotel_code, string $booking_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            // Soft-cancel booking by setting state
            $payload = ['id' => $booking_id, 'state' => 'cancelled'];
            $service->folioUpdate($payload);
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function addRoom(Request $request, string $hotel_code, string $booking_id)
    {
        $data = $request->all();
        $payload = [
            'folio' => ['id' => $booking_id],
            'rooms' => is_array($data) && isset($data[0]) ? $data : [$data],
        ];
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $res = $service->folioAddReservation($payload);
            return response()->json($res, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateRoom(Request $request, string $hotel_code, string $booking_id, int $reservation_id)
    {
        $data = $request->all();
        $payload = array_merge($data, ['id' => $reservation_id]);
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $res = $service->reservationUpdate($payload);
            return response()->json($res);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
