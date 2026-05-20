<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use App\Events\CapturePaymentResponse;

use App\Services\HotelApiService;
use App\Models\EventoHomeTotem;
use Mail;
use App\Mail\ParteViajeroMail;
use App\Models\Avail;
use App\Models\ControlError;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use App\Services\ParteViajeroService;
use App\Services\AliceService;
use App\Services\UtilService;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;


class TestController extends Controller
{

    public function index(Request $request, $opc)
    {
        if ($opc == 'alice') {
            return $this->alice($request);
        }
        if ($opc == 'scan') {
            return $this->scan($request);
        }
        if ($opc == 'observer') {
            return $this->observer($request);
        }
        if ($opc == 'avails') {
            return $this->avails($request);
        }
        /*echo storage_path('aa');die;
        $emails = ['oshunu@gmail.com'];
        //Mail::to($emails)->send(new ParteViajeroMail());

        
       // $checkIns = ParteViajeroService::pdfGenerate(796015);
        //dd($checkIns);
        
        return view('test.parte_pdf', compact('checkIns'));*/
    }
    
    /* metodo para generar habitaciones disponibles*/
    public function avails(Request $request)
    {
        $hotel_id = 5;
        $from = '2025-02-15';
        $to = '2025-02-21';

        $startDate = new DateTime($from);
        $endDate = new DateTime($to);
        $endDate->modify('+1 day');
        $dateInterval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $dateInterval, $endDate);

        $room_types = RoomType::where('establecimiento_id', $hotel_id)->get();
        $rooms = Room::where('establecimiento_id', $hotel_id)->get();

        foreach ($dateRange as $date) {
            $fecha = $date->format('Y-m-d');
            foreach($room_types as $rt){
                RoomTypePrice::create([
                    'establecimiento_id' => $hotel_id,
                    'room_type_id' => $rt->id,
                    'date' => $fecha,
                    'price' => mt_rand(0, 9) .'0'
                ]);
                
            }  
            foreach($rooms as $room){
                Avail::create([
                    'establecimiento_id' => $hotel_id,
                    'room_id' => $room->id,
                    'date' => $fecha,
                    'available' => 1
                ]);         
            }
        }
        die;
    }

    public function scan(Request $request)
    {
        return view('test.scan');
    }

    public function alice($data)
    {

        
        $hotel_api = new HotelApiService(3);
        $roomdo_paises = $hotel_api->countries();

        dump(count($roomdo_paises));
        

        $sh360_paises = UtilService::countries_all();
        dump(count($sh360_paises));

        $new_list = [];
        foreach($roomdo_paises as $id => $pais){
            $first = Arr::first($sh360_paises, function ($value, int $key) use ($pais) {
                return $value['country_code'] == $pais['code'];
            });
            if ($first) {
                $first['id'] = $pais['id'];
                $new_list[] = $first;
               // $alice_paises[$ka]['code_roomdo'] = $first['id'];
            } else {
                //dump($pais);
            }
        }
        usort($new_list, function ($a, $b) {
            return strcmp($a['country'], $b['country']);
        });
        
        echo '<pre>';
        foreach ($new_list as $item) {
            echo '["id" => "' . $item['id'] . '", "country" => "' . $item['country'] . '", "country_code" => "' . $item['country_code'] . '", "code" => "' . $item['code'] . '"],' . PHP_EOL;
        }
        echo '</pre>';
        die;

        foreach ($alice_paises as $ka => $ra) {
            $first = Arr::first($roomdo_paises, function ($value, int $key) use ($ra) {
                return $value['code'] == $ra['country_code'];
            });
            if ($first) {
                $alice_paises[$ka]['code_roomdo'] = $first['id'];
            } else {
                $alice_paises[$ka]['code_roomdo'] = 'no_id';
            }
        }
        dd($alice_paises);
        dump($roomdo_paises[1]);
        //dump($alice_paises);
        //die;

        foreach ($roomdo_paises as $ka => $ra) {
            $first = Arr::first($alice_paises, function ($value, int $key) use ($ra) {
                if (isset($value['country_code'])) {
                    return $value['country_code'] == $ra['code'];
                } else {
                    dd('error country_code');
                }
            });
            if ($first) {
                $roomdo_paises[$ka]['alice_code'] = $first['country_code'];
            } else {
                $roomdo_paises[$ka]['alice_code'] = 'no_id';
            }
        }
        dd($roomdo_paises);

        die;
    }
    public function pdf()
    {

        $pdf = \App::make('dompdf.wrapper');
        //$pdf->loadHTML('<h1>Test</h1>');
        //return $pdf->stream();

        $foto = EventoHomeTotem::where('id', 128)->first();
        $firma = $foto->respuesta_texto;
        //   dd($firma);

        $filename = public_path() . '/parte-viajero/my_stored_file.pdf';
        $pdf->loadView('admin/pdf/parte-test', ['firma' => $firma]);
        return $pdf->stream();
        // $pdf->save($filename);
    }

    public function login()
    {
        $data = [
            'username' => 'neotech360@roomdoo.com',
            'password' => '526XVZqBjY3tKG'
        ];
        //$a = HotelApiService::login($data);
        //dd($a);
    }


    public function pago_respuesta_ok()
    {
        $pago_origen_id = 376;
        $receptor_pago = PagoTotem::where('id', $pago_origen_id)->first();

        $respuesta_xml = '<Operaciones version="6.0"><resultadoOperacion><tipoPago>PAGO</tipoPago><importe>10.00</importe><moneda>978</moneda><tarjetaClienteRecibo>************3338</tarjetaClienteRecibo><tarjetaComercioRecibo>************3338</tarjetaComercioRecibo><marcaTarjeta>1</marcaTarjeta><caducidad>0000</caducidad><comercio>347874323</comercio><terminal>2</terminal><pedido>1188</pedido><tipoTasaAplicada>DEB</tipoTasaAplicada><identificadorRTS>075002240215141539086551</identificadorRTS><factura>prueba t pin</factura><fechaOperacion>2024-02-15 14:15:38.0</fechaOperacion><estado>F</estado><resultado>Autorizada</resultado><codigoRespuesta>46546</codigoRespuesta><firma>40396A20CF903A02D38A40812ADA986C52E97DDBA10FF54A9FB9CBF858FCFE83</firma><operacionemv>true</operacionemv><resverificacion>0080048000</resverificacion><conttrans>000025</conttrans><sectarjeta>01</sectarjeta><idapp>A0000000031010</idapp><DDFName>A0000000031010</DDFName><etiquetaApp>Visa Clasica</etiquetaApp><codrespauto>00</codrespauto><autenticadoPorPin>TRUE</autenticadoPorPin><Literales><autenticadoPorPin>OPERACION CON PIN. FIRMA NO NECESARIA.</autenticadoPorPin></Literales></resultadoOperacion></Operaciones>';

        $respuesta_xml = '<Operaciones version="6.0"><resultadoOperacion><tipoPago>PAGO</tipoPago><importe>15.00</importe><moneda>978</moneda><tarjetaClienteRecibo>************0037</tarjetaClienteRecibo><tarjetaComercioRecibo>************0037</tarjetaComercioRecibo><marcaTarjeta>2</marcaTarjeta><caducidad>0000</caducidad><comercio>347874323</comercio><terminal>2</terminal><pedido>1442</pedido><identificadorRTS>070001241022135100728941</identificadorRTS><factura>224/24/012413</factura><fechaOperacion>2024-10-22 13:51:00.0</fechaOperacion><titularTarjeta>MTIP04</titularTarjeta><estado>F</estado><resultado>Autorizada</resultado><codigoRespuesta>763300</codigoRespuesta><tipoCSB>0</tipoCSB><adquirenteCSB>6874</adquirenteCSB><bin8Tarjeta>54133300</bin8Tarjeta><firma>53EDD44B4253D82D75304123FDF32BB53AFAC7D1AAF059206E3052C31A08EEA3</firma><ReciboSoloCliente>TRUE</ReciboSoloCliente></resultadoOperacion></Operaciones>';

        $xmlObject = simplexml_load_string($respuesta_xml);
        $json = json_encode($xmlObject);
        $phpArray = json_decode($json, true);

        $objeto = $phpArray['resultadoOperacion'];
        $emisor_id = 15;
        $receptor_id = 2;

        $capture_xml = collect([
            'pago_origen_id'    => $pago_origen_id,
            'estado'            => $objeto['estado'],
            'resultado'         => $objeto['resultado'],
            'codigoRespuesta'   => $objeto['codigoRespuesta'],
            'emisor_id'         => $emisor_id,
            'receptor_id'       => $receptor_id,
            'comercio'          => $objeto['comercio'],
            'pedido'            => $objeto['pedido'],
            'terminal'          => str_pad($objeto['terminal'], 3, "0", STR_PAD_LEFT),
            'etiquetaApp'       => $objeto['etiquetaApp'] ?? '',
            'importe'           => $objeto['importe'],
            'tarjeta'           => $objeto['tarjetaComercioRecibo'],
            'fecha'             => $objeto['fechaOperacion'],
            'firma'             => $objeto['firma'],
            'Literales'         => $objeto['Literales'] ?? [],

            'idapp'             => $objeto['idapp'] ?? '',
            'conttrans'         => $objeto['conttrans'] ?? '',
            'codrespauto'       => $objeto['codrespauto'] ?? '',
            'resverificacion'   => $objeto['resverificacion'] ?? '',
            "oper_contact_less" => isset($objeto['operContactLess']) ? $objeto['operContactLess'] : '',
        ]);

        event(new CapturePaymentResponse($capture_xml));
    }

    public function observer($request)
    {


        /*$controlError = ControlError::create([
            'origen' => 'test  ' . time(),
            'tipo' => 'test_observer',
            'mensaje' => 'tipo observer'            
        ]);*/

        $hotel_api = new HotelApiService(3);
    }
}
