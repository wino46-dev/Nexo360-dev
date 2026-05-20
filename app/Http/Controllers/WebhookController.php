<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function storeReservation(Request $request)
    {
        $secret = env('WEBHOOK_SECRET');
                
        if ($request->header('X-API-KEY') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        // Guardar el payload completo como JSON
        $webhooklog = WebhookLog::create([
            'type' => 'reservation',
            'status' => 1,
            'payload' => $data
        ]);

        WebhookService::process($webhooklog->id);


        return response()->json(['status' => 'ok']);
    }
}
