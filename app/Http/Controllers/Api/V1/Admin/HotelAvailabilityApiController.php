<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\HotelApiService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelAvailabilityApiController extends Controller
{
    /**
     * Return availability by room type between two dates for a given hotel.
     * It uses the PMS-agnostic HotelApiService::availsByRoomType.
     */
    public function availsByRoomType(Request $request)
    {
        abort_if(Gate::denies('reserva_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'establecimiento_id' => 'required|integer|exists:establecimientos,id',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
            // Optional filters/params (passed through to PMS if supported)
            'adults' => 'nullable|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'rooms' => 'nullable|integer|min:1',
            'room_type_ids' => 'nullable|array',
            'room_type_ids.*' => 'integer',
            // allow any extra passthrough fields without validation errors
        ]);

        $hotelApi = new HotelApiService((int)$data['establecimiento_id']);

        // Build payload with known and any extra passthrough params
        $payload = [
            'date_start' => $data['date_start'],
            'date_end' => $data['date_end'],
        ];
        foreach (['adults','children','rooms','room_type_ids'] as $opt) {
            if ($request->has($opt)) {
                $payload[$opt] = $request->input($opt);
            }
        }
        // Include any additional dynamic fields prefixed with x_ to allow PMS-specific options
        foreach ($request->all() as $k => $v) {
            if (!array_key_exists($k, $payload) && !in_array($k, ['establecimiento_id'])) {
                $payload[$k] = $v;
            }
        }

        $avails = $hotelApi->availsByRoomType($payload);
        if (is_array($avails) && array_key_exists('error', $avails)) {
            return response()->json($avails, 500);
        }

        return response()->json(['data' => $avails]);
    }
}
