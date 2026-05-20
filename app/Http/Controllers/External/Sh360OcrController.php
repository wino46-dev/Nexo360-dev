<?php

namespace App\Http\Controllers\External;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventoHomeTotem;
use App\Services\Sh360OcrService;
use Illuminate\Support\Facades\Cache;


class Sh360OcrController extends Controller
{
    public function index(Request $request, $id)
    {
        $checkin_id = $id;

        $foto1 = EventoHomeTotem::where('objeto', $checkin_id)
            ->where('tipo_evento_id', 3)
            ->orderBy('id', 'DESC')
            ->first();


        $foto2 = EventoHomeTotem::where('objeto', $checkin_id)
            ->where('tipo_evento_id', 6)
            ->orderBy('id', 'DESC')
            ->first();
        $hotel_id = session('api_establecimiento_id');
        $sh360Ocr = new Sh360OcrService($hotel_id);

        /*$data_ocr = Cache::remember('data_ocr_' . $checkin_id, 1000, function () use ($sh360Ocr, $foto1, $foto2) {
            return $sh360Ocr->getData([$foto1, $foto2]);
        });*/

        $data_ocr = $sh360Ocr->getData([$foto1, $foto2]);

        $data_ocr2 = [
            "message" => "Success",
            "pdf_pages_amount" => 0,
            "initial_pages" => 1.0,
            "img_amount" => 2,
            "processing_cost" => 0.02,
            "ocr_cost" => 0.0015,
            "ai_cost" => 0.000961,
            "total_cost" => 0.022461000000000002,
            "remaining_customer_balance" => 6.97989,
            "result_ocr" => [
                "ESPAÑA O DOCUMENTO NACIONAL DE OSTIDAD",
                "APELLIDOS/CONS",
                "RIBOT",
                "FONT",
                "MARIA DOLORS",
                "NADIONALIDAD NACIONALIT",
                "ESP",
                "FECHA DE SAMENTO/DATA DE ASIMON",
                "20 02 1989",
                "NUW SOPORT",
                "VALDEZ/ALES",
                "BAY124948 18 02 2021",
                "DNI 41535229N",
                "COOLIO COM",
                "C. BENITO PEREZ GALDOS 3 P01 C",
                "PALMA DE MALLORCA",
                "ILLES BALEARS",
                "anos/Ganca",
                "155712",
                "07601A6D1",
                "LUGAR DE NAOMENTO LLOC OF M",
                "PETRA",
                "ILLES BALEARS",
                "MUDA DE FILLIA DE",
                "ANTONI CATALINA AINA",
                "IDESPBAY124948141535229N<<<<<<",
                "8902203F2102182ESP<<<<<<<<<<<4",
                "RIBOT<FONT<<MARIA<DOLORS<<<<<<"
            ],
            "result_formated" => [
                "tipo_documento" => "DNI",
                "pais_documento" => "ESP",
                "numero_identificacion" => "41535229N",
                "apellidos" => "RIBOT FONT",
                "nombre" => "MARIA DOLORS",
                "sexo" => "M",
                "nacionalidad" => "ESP",
                "fecha_nacimiento" => "20/02/1989",
                "validez_documento" => "18/02/2021",
                "direccion" => "C. BENITO PEREZ GALDOS 3 P01 C",
                "poblacion" => "PALMA DE MALLORCA",
                "provincia" => "ILLES BALEARS",
                "lugar_nacimiento" => "PETRA",
                "hijo_de" => "ANTONI CATALINA AINA"
            ]
        ];
        // $data_ocr = $data_ocr2;

        return view('external.sh360-ocr.index', compact('checkin_id', 'data_ocr','hotel_id'));
    }
}
