<?php

namespace App\Http\Middleware;

use App\Models\Establecimiento;
use Closure;
use Illuminate\Http\Request;

class ApiPmsAuth
{
    public function handle(Request $request, Closure $next)
    {
        $hotelCode = (string) $request->route('hotel_code');
        $apiKey = (string) $request->header('X-API-KEY');

        if ($hotelCode === '' || $apiKey === '') {
            return response()->json([
                'code' => 'unauthorized',
                'message' => 'Invalid X-API-KEY or hotel_code',
            ], 401);
        }

        $hotel = Establecimiento::where('codigo', $hotelCode)->first();
        if (!$hotel || !$hotel->api_access_key || !hash_equals((string)$hotel->api_access_key, $apiKey)) {
            return response()->json([
                'code' => 'unauthorized',
                'message' => 'Invalid X-API-KEY or hotel_code',
            ], 401);
        }

        // Inject multi-tenant context
        $request->attributes->set('establecimiento_id', $hotel->id);
        $request->attributes->set('establecimiento', $hotel);

        return $next($request);
    }
}
