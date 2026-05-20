<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;
use App\Models\CheckIn;

use App\Models\Establecimiento;
use App\Models\ControlSesion as ControlSesionModel;
use App\Models\User;
use App\Models\Sociedad;
use App\Models\Reserva;

use App\Models\EventoHomeTotem;

use App\Events\SendTotemHomeEvent;
use App\Models\Reservation;

class ParteViajeroService
{

    public static function pdfGenerate($reservation_id, $checkin_id = null, $pms)
    {
        if ($pms != 'local') {
            $reservation = Reservation::where('remote_id', $reservation_id)
                ->where('pms', $pms)
                ->first();
            $reservation_id = $reservation->id;

            $checkIns = CheckIn::where('reservation_id', $reservation_id);
            if (!empty($checkin_id)) {
                $checkIns->where('remote_id', $checkin_id);
            }
            $checkIns = $checkIns->get();

        } else {
            $checkIns = CheckIn::where('reservation_id', $reservation_id);
            if (!empty($checkin_id)) {
                $checkIns->where('id', $checkin_id);
            }
            $checkIns = $checkIns->get();
        }
        Log::info('----');
        Log::info($reservation_id);
        Log::info($checkin_id);
        Log::info('----');

        $emisors = auth()->user()->id;

        $current_user_sesion = ControlSesionModel::where('estado_sesion', 1)->where('emisor_id', $emisors)->with(['receptor'])->first();

        if (isset($current_user_sesion->receptor_id)) {
            $usuarioTotem = User::where('id', $current_user_sesion->receptor_id)->with(['totem'])->first();
        }
        if (isset($usuarioTotem->totem->id)) {
            $establecimiento = Establecimiento::where('id', $usuarioTotem->totem->establecimiento_id)->first();
            $sociedad = Sociedad::where('id', $establecimiento->sociedad_id)->first();
        }

        $reservation = Reservation::where('id', $reservation_id)->first();

        //dd($reservation);

        foreach ($checkIns as $checkIn) {

            self::pdfGenerateFile(
                $checkIn,
                $current_user_sesion,
                $establecimiento,
                $reservation,
                $sociedad
            );
        }

        $event = [
            'tipo_evento_id' => 7,
            //'id'             => $firmaCheckIn->id,
            'sesion_id'      => $current_user_sesion->id,
            'receptor_id'    => $current_user_sesion->receptor_id,
            'emisor_id'    => $current_user_sesion->emisor_id,
            'canal_transmision' => 'Home Inferior',
            'objeto' => $reservation_id,
            'mensaje' => $reservation_id,
            'pms' =>  $establecimiento->api_pms
        ];
        $eventoHomeTotem = EventoHomeTotem::create($event);

        if (isset($eventoHomeTotem->id)) {
            event(new SendTotemHomeEvent($event));
        }
        return $checkIns;
    }

    public static function pdfGenerateFile(
        $checkIn,
        $current_user_sesion,
        $establecimiento,
        $reservation,
        $sociedad
    ) {

        $pdf = \App::make('dompdf.wrapper');

        $evento_firma = EventoHomeTotem::where('objeto', $checkIn->remote_id)
            ->where('sesion_id', $current_user_sesion->id)
            ->where('tipo_evento_id', 4)->first();
        $firma_img = $evento_firma->respuesta_texto ?? '';

        $filename = public_path() . '/parte-viajero/parte_viajero_' . $checkIn->id . '.pdf';
        $pdf->loadView('admin/pdf/parte', [
            'checkin' => $checkIn,
            'establecimiento' => $establecimiento,
            'reservation' => $reservation,
            'sociedad' => $sociedad,
            'firma_img' => $firma_img,
        ]);
        $pdf->save($filename);
    }
}
