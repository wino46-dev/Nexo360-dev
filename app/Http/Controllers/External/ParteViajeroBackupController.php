<?php

namespace App\Http\Controllers\External;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\EventoHomeTotem;
use App\Services\Sh360OcrService;
use Illuminate\Support\Facades\Cache;


class ParteViajeroBackupController extends Controller
{
    public function ftp(Request $request, $date)
    {
        $checkins = CheckIn::where('hotel_id', session('api_establecimiento_id'))
            ->where('created_at', '>=', $date . ' 00:00:00')
            ->where('created_at', '<=', $date . ' 23:59:59')
            ->get();
        $checkin_ids = $checkins->pluck('id')->toArray();


        foreach($checkin_ids as $checkin){
            Storage::disk('tb_ftp')->put($rutaDestino . $nombreSinExtension . '.tmp', file_get_contents($rutaTemporal));
        }


    }

}
