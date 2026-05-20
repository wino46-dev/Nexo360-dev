<?php

namespace App\Http\Controllers\Api\LocalPms;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    // GET /bookings/{booking_id}/services
    public function index(Request $request, string $hotel_code, string $booking_id)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            // In our providers, reservationServices expects reservation id; allow client to pass reservation_id via query, fallback to booking_id
            $reservationId = $request->query('reservation_id', $booking_id);
            $items = $service->reservationServices($reservationId);
            return response()->json(['services' => $items ?? []]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // POST /bookings/{booking_id}/services
    public function store(Request $request, string $hotel_code, string $booking_id)
    {
        // Not yet implemented in Local PMS: adding service items via API
        return response()->json([
            'code' => 'not_supported',
            'message' => 'Adding reservation services is not implemented for Local PMS yet.',
        ], 501);
    }

    // DELETE /bookings/{booking_id}/services/{service_id}
    public function destroy(Request $request, string $hotel_code, string $booking_id, string $service_id)
    {
        return response()->json([
            'code' => 'not_supported',
            'message' => 'Removing reservation services is not implemented for Local PMS yet.',
        ], 501);
    }
}
