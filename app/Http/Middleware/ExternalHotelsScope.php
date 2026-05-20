<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class ExternalHotelsScope
{
    /**
     * Handle an incoming request for the external backoffice.
     * - Computes allowed hotel IDs for the authenticated user.
     * - Shares them in config('external.allowed_hotels') for this request.
     * - Optionally blocks write operations if they reference non-allowed hotels.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Only enforce for external users
        if (method_exists($user, 'isExternal') && $user->isExternal()) {
            // Gather allowed establecimiento (hotel) IDs from user relation
            $allowedHotelIds = $user->establecimientos()->pluck('establecimientos.id')->toArray();
            // Share in config for downstream code (services, views, etc.)
            Config::set('external.allowed_hotels', $allowedHotelIds);

            // Add runtime global scope to Establecimiento and Reservation so any list is filtered
            try {
                \App\Models\Establecimiento::addGlobalScope('externalHotels', function ($builder) use ($allowedHotelIds) {
                    // If user has no explicit hotels, show nothing
                    $ids = $allowedHotelIds ?: [0];
                    $builder->whereIn('establecimientos.id', $ids);
                });
            } catch (\Throwable $e) {
                // ignore if model not loaded yet
            }

            // Basic guard for common payload fields carrying hotel/establecimiento
            if (in_array($request->method(), ['POST','PUT','PATCH'])) {
                $blocked = $this->violatesHotelScope($request, $allowedHotelIds);
                if ($blocked) {
                    abort(403, 'Acción no permitida para hoteles no autorizados.');
                }
            }
        }

        return $next($request);
    }

    /**
     * Heuristic checks for common parameter names containing hotel/establecimiento identifiers
     * used by reservation, check-in, and related endpoints.
     */
    protected function violatesHotelScope(Request $request, array $allowed): bool
    {
        // If user has no allowed hotels, disallow writes that target any hotel
        $hasAllowed = count($allowed) > 0;

        $payload = $request->all();

        $candidateIds = [];
        // direct fields
        foreach (['hotel_id', 'establecimiento_id'] as $key) {
            if (isset($payload[$key]) && is_scalar($payload[$key])) {
                $candidateIds[] = (int) $payload[$key];
            }
        }

        // nested structures commonly used by reservas
        if (isset($payload['rooms']) && is_array($payload['rooms'])) {
            foreach ($payload['rooms'] as $room) {
                if (is_array($room) && isset($room['hotel_id'])) {
                    $candidateIds[] = (int) $room['hotel_id'];
                }
            }
        }

        if (isset($payload['folios']) && is_array($payload['folios'])) {
            foreach ($payload['folios'] as $folio) {
                if (is_array($folio) && isset($folio['hotel_id'])) {
                    $candidateIds[] = (int) $folio['hotel_id'];
                }
            }
        }

        $candidateIds = array_unique(array_filter($candidateIds));

        if (!$hasAllowed && count($candidateIds) > 0) {
            return true;
        }
        if (count($candidateIds) === 0) {
            return false; // no hotel-specific write detected
        }

        foreach ($candidateIds as $id) {
            if (!in_array($id, $allowed)) {
                return true;
            }
        }
        return false;
    }
}
