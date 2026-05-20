<?php

namespace App\Services;

use App\Models\Establecimiento;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str as Str;

use Carbon\Carbon;

class WebhookService
{

    public static function process($id)
    {
        $webhooklog = WebhookLog::find($id);
        if (!$webhooklog) {
            Log::error('Webhook no encontrado', [
                'id' => $id
            ]);
            return;
        }
        $reservation = $webhooklog->payload;
        $hotel_id = null;
        if (isset($reservation['booking']['hotel_code'])) { // para misterplan
            $hotel_id = $reservation['booking']['hotel_code'];
        }

        if ($hotel_id == null) {
            Log::error('Hotel ID no encontrado en el payload', [
                'payload' => $reservation
            ]);
            return;
        }
        $establecimiento = Establecimiento::where('api_pms', 'misterplan')
            ->where('remote_hotel_id', $hotel_id)
            ->first();

        if (!$establecimiento) {
            Log::error('Establecimiento no encontrado', [
                'hotel_id' => $hotel_id
            ]);
            return;
        }


        //$establecimiento_id = $establecimiento->id;

        if ($establecimiento->api_pms == 'misterplan') {
            $service = new MisterPlanApiService($establecimiento);
            $res = $service->importWebhook($reservation['booking']);

        }
    }

}
