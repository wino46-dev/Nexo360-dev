<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;
use App\Models\RespuestaPago;

use App\Models\Establecimiento;
use App\Models\ControlSesion as ControlSesionModel;
use App\Models\User;
use App\Models\PagoTotem;
use App\Models\Totem;

use App\Models\EventoHomeTotem;

use App\Events\SendTotemHomeEvent;
use App\Models\Sociedad;

class PagoService
{

    public static function pdfGenerate($pago_origen_id)
    {

        $pago = RespuestaPago::where('pago_origen_id', $pago_origen_id)->first();

        $pago_origen = PagoTotem::where('id', $pago_origen_id)->first();

        $totem_base = User::where('id', $pago_origen->receptor_id)->first();
        $totem = Totem::where('id', $totem_base->totem_id)->first();

        $establecimiento = Establecimiento::where('id', $totem->establecimiento_id)->first();
        $sociedad = Sociedad::where('id', $establecimiento->sociedad_id)->first();


        $pdf = \App::make('dompdf.wrapper');

        $url_name = '/parte-viajero/pago_' . $pago_origen_id . '.pdf';
        $filename = public_path() . $url_name;
        $pdf->setPaper([0, 0, 360, 590], 'portrait');
        $pdf->loadView('admin/pdf/pago', [
            'establecimiento' => $establecimiento,
            'sociedad' => $sociedad,
            'pago' => $pago
        ]);
        $pdf->save($filename);
        return $url_name;
    }
}
