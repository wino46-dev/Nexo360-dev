<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CallManagerReservationApiController extends Controller
{
    /**
     * Search folios by guest name between dates for a given hotel (establecimiento).
     * Mirrors Call Manager flow step 1 using HotelApiService::folioSearch.
     */
    public function searchFolios(Request $request)
    {
        // Require permission similar to reservas API
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'establecimiento_id' => 'required|integer|exists:establecimientos,id',
            'q' => 'nullable|string',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
            'type' => 'nullable|string',
        ]);

        $hotelApi = new HotelApiService((int)$data['establecimiento_id']);

        // Build payload accepted by PMS services
        $payload = [];
        if (!empty($data['q']) || isset($data['date_start']) || isset($data['date_end'])) {
            $payload = [
                'q' => $data['q'] ?? null,
                'date_start' => $data['date_start'] ?? null,
                'date_end' => $data['date_end'] ?? null,
            ];
        } elseif (!empty($data['type'])) {
            $payload = ['type' => $data['type']];
        }

        $folios = $hotelApi->folioSearch($payload);
        if (is_array($folios) && array_key_exists('error', $folios)) {
            return response()->json($folios, 500);
        }

        return response()->json(['data' => $folios]);
    }

    /**
     * Get folio detail by folio ID for a given hotel (step 2 in flow).
     */
    public function folioDetail(Request $request, $folioId)
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'establecimiento_id' => 'required|integer|exists:establecimientos,id',
        ]);

        $hotelApi = new HotelApiService((int)$data['establecimiento_id']);
        $folio = $hotelApi->folioDetail($folioId);

        if (is_array($folio) && array_key_exists('error', $folio)) {
            return response()->json($folio, 500);
        }

        return response()->json(['data' => $folio]);
    }

    /**
     * Get reservation detail (usually includes lines) by reservation ID for a given hotel (step 3 in flow).
     */
    public function reservationDetail(Request $request, $reservationId)
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'establecimiento_id' => 'required|integer|exists:establecimientos,id',
        ]);

        $hotelApi = new HotelApiService((int)$data['establecimiento_id']);
        $reservation = $hotelApi->reservationDetail($reservationId);

        if (is_array($reservation) && array_key_exists('error', $reservation)) {
            return response()->json($reservation, 500);
        }

        return response()->json(['data' => $reservation]);
    }
}
