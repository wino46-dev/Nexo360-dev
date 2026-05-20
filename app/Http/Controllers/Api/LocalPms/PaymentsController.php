<?php

namespace App\Http\Controllers\Api\LocalPms;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    // GET /payments/methods
    public function methods(Request $request, string $hotel_code)
    {
        try {
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $methods = $service->accountJournals();
            return response()->json(['methods' => $methods]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // GET /payments/transactions
    public function transactions(Request $request, string $hotel_code)
    {
        try {
            $filters = [
                'from' => $request->query('from'),
                'to' => $request->query('to'),
                'method_id' => $request->query('method_id'),
                'booking_id' => $request->query('booking_id'),
            ];
            $hotelId = (int) $request->attributes->get('establecimiento_id');
            $service = new HotelApiService($hotelId);
            $tx = $service->transactions($filters);
            return response()->json(['transactions' => $tx]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 'server_error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // POST /payments/charges
    public function createCharge(Request $request, string $hotel_code)
    {
        // For now, the Local PMS does not implement payment capture flows.
        // Expose the endpoint with a clear 501 to keep API parity and documentation complete.
        return response()->json([
            'code' => 'not_supported',
            'message' => 'Payments capture is not implemented for Local PMS yet.',
        ], 501);
    }

    // POST /payments/refunds
    public function refund(Request $request, string $hotel_code)
    {
        return response()->json([
            'code' => 'not_supported',
            'message' => 'Payments refund is not implemented for Local PMS yet.',
        ], 501);
    }
}
