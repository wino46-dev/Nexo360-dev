<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Establecimiento;
use Gate;
use Illuminate\Http\Response;

class LandingWikiHotelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wiki_hotel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $establecimientos = Establecimiento::orderBy('nombre', 'asc')->get(['id','nombre','codigo']);

        // Preselect establecimiento from active Control de Sesión (if exists)
        $defaultEstablecimientoId = null;
        $defaultEstablecimientoCodigo = null;
        $hasActiveControlSesion = false;
        try {
            $emisors = auth()->user()->id;
            $current = \App\Models\ControlSesion::where('estado_sesion', 1)
                ->where('emisor_id', $emisors)
                ->with(['receptor.totem.establecimiento'])
                ->first();
            if ($current && $current->receptor && $current->receptor->totem) {
                $defaultEstablecimientoId = $current->receptor->totem->establecimiento_id ?? null;
                $defaultEstablecimientoCodigo = optional($current->receptor->totem->establecimiento)->codigo;
                $hasActiveControlSesion = !empty($defaultEstablecimientoId);
            }
        } catch (\Throwable $e) {
            // ignore if table/relations not available in some environments
        }

        return view('admin.landingWikiHotel.index', compact('establecimientos','defaultEstablecimientoId','defaultEstablecimientoCodigo','hasActiveControlSesion'));
    }
}
