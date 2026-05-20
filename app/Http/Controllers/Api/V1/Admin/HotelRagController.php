<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Establecimiento;
use Illuminate\Http\Request;

class HotelRagController extends Controller
{
    /**
     * GET /api/v1/hotel-rag
     * Also supports /api/v1/hotel-rag/{id}
     * Params:
     *  - hotel_id (int) or codigo (string)
     * Returns aggregated data for a hotel: establecimiento, sociedad, totems and Wiki Hotel tabs.
     */
    public function show(Request $request)
    {
        $hotelId = $request->integer('hotel_id');
        $codigo = $request->get('codigo');

        $query = Establecimiento::query()
            ->with([
                'sociedad',
                'establecimientoTotems',
                'establecimientoDocHotelReceptionInfos',
                'hotelDocInfoHotels',
                'hotelDocServicioHotels',
                'establecimientoDocMetodoPagoHotels',
                'establecimientoDocUbicacionHotels',
                'establecimientoDocIncidenciaHotels',
                'establecimientoDocNoDeseadoHotels',
                'establecimientoDocHotelEstadoCajas',
                'establecimientoDocHabitacionHotels',
                'establecimientoDocTarifaHotels',
            ]);

        // Enforce external allowed hotels scope if configured (used by ExternalHotelsScope middleware)
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed)) {
            $ids = count($allowed) ? $allowed : [0];
            $query->whereIn('establecimientos.id', $ids);
        }

        if ($hotelId) {
            $query->where('establecimientos.id', $hotelId);
        } elseif ($codigo) {
            $query->where('establecimientos.codigo', $codigo);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Debe indicar hotel_id o codigo.'
            ], 422);
        }

        $hotel = $query->first();

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel no encontrado.'
            ], 404);
        }

        // Build response in a clear structure
        return response()->json([
            'status' => 'ok',
            'data' => [
                'establecimiento' => $hotel, // includes appended media attributes
                'sociedad' => $hotel->sociedad,
                'totems' => $hotel->establecimientoTotems,
                'wiki' => [
                    'recepcion' => $hotel->establecimientoDocHotelReceptionInfos,
                    'info_hotel' => $hotel->hotelDocInfoHotels,
                    'servicios' => $hotel->hotelDocServicioHotels,
                    'metodos_pago' => $hotel->establecimientoDocMetodoPagoHotels,
                    'ubicacion' => $hotel->establecimientoDocUbicacionHotels,
                    'incidencias' => $hotel->establecimientoDocIncidenciaHotels,
                    'no_deseado' => $hotel->establecimientoDocNoDeseadoHotels,
                    'estado_caja' => $hotel->establecimientoDocHotelEstadoCajas,
                    'habitaciones' => $hotel->establecimientoDocHabitacionHotels,
                    'tarifas' => $hotel->establecimientoDocTarifaHotels,
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/hotel-rag/{id}
     * Thin wrapper to support path-parameter style by delegating to show().
     */
    public function showById($id, Request $request)
    {
        $request->merge(['hotel_id' => (int) $id]);
        return $this->show($request);
    }

    /**
     * GET /api/v1/list-hotels-rag
     * Returns a lightweight list of establecimientos with only id, codigo, nombre.
     */
    public function listHotels(Request $request)
    {
        // Use query builder to avoid model accessors/appends adding extra data
        $qb = \Illuminate\Support\Facades\DB::table('establecimientos')
            ->select(['establecimientos.id', 'establecimientos.codigo', 'establecimientos.nombre']);

        // Enforce external allowed hotels scope if configured
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed)) {
            $ids = count($allowed) ? $allowed : [0];
            $qb->whereIn('establecimientos.id', $ids);
        }

        $hoteles = $qb->orderBy('nombre', 'asc')->get();

        return response()->json([
            'status' => 'ok',
            'data' => $hoteles,
        ]);
    }
}
