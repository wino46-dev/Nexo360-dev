<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\CapturePaymentResponse;
use App\Events\WriteCardEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRespuestaPagoRequest;
use App\Http\Requests\UpdateRespuestaPagoRequest;
use App\Http\Resources\Admin\RespuestaPagoResource;
use App\Models\PagoTotem;
use App\Models\RespuestaPago;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Services\HotelApiService;
use App\Models\ControlError;
use App\Models\User;


class RespuestaPagoApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('respuesta_pago_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespuestaPagoResource(RespuestaPago::with(['pago_origen'])->get());
    }

    public function store(StoreRespuestaPagoRequest $request)
    {        
        try {

            $receptor_pago = PagoTotem::where('id', $request->pago_origen_id)->first();
            
            if (isset($receptor_pago->receptor_id)) {
                $xmlObject = simplexml_load_string($request->respuesta_xml);
                $json = json_encode($xmlObject);
                $phpArray = json_decode($json, true);
                

                if (isset($phpArray['resultadoOperacion'])) {

                    $objeto = $phpArray['resultadoOperacion'];
                    

                    if (isset($objeto['resultado']) && $objeto['resultado'] == 'Autorizada') {
                        try {
                            
                            $usuarioTotem = User::where('id', $receptor_pago->receptor_id)->with(['totem'])->first();
                            
                            if (!isset($usuarioTotem->totem->id)) {
                               // return 'error totem_id';
                            }                                                       
                            
                            $hotel_api = new HotelApiService($usuarioTotem->totem->establecimiento_id);
                            
                            //$accountJournals = $hotel_api->accountJournals();
                            
                            $journalId = $usuarioTotem->totem->establecimiento->pms_payment_method_totem;

                            $pago_api_data = [
                                'journalId' => $journalId ?? '0',
                                'amount' => floatval($receptor_pago->importe),
                                'folioId' => intval($receptor_pago->folio_id),
                                'transactionType' => "customer_inbound",
                                'reference' => "Pago desde Totem - " . $receptor_pago->factura,
                                'date' => date('Y-m-d'),
                            ];
                            $hotel_api->transactions($pago_api_data);

                        } catch (\Exception $e) {
                            ControlError::create([
                                'origen' => 'RoomdooApiService',
                                'tipo' => 'Pago Api',
                                'mensaje' => 'Error al realizar pago en la api',
                                'descripcion' => 'Folio: ' . intval($receptor_pago->folio_id) . ' - '. $receptor_pago->factura,
                                'establecimiento_id' => $usuarioTotem->totem->establecimiento_id ?? null
                            ]);
                        }
                    }
                    
                    $data = [
                        "tipoPago" => isset($objeto['tipoPago']) ? $objeto['tipoPago'] : '',
                        "importe" => isset($objeto['importe']) ? $objeto['importe'] : '',
                        "moneda" => isset($objeto['moneda']) ? $objeto['moneda'] : '',
                        "tarjeta_comercio_ecibo" => isset($objeto['tarjetaComercioRecibo']) ? $objeto['tarjetaComercioRecibo'] : '',
                        "tarjeta_cliente_recibo" => isset($objeto['tarjetaClienteRecibo']) ? $objeto['tarjetaClienteRecibo'] : '',
                        "marca_tarjeta" => isset($objeto['marcaTarjeta']) ? $objeto['marcaTarjeta'] : '',
                        "caducidad" => isset($objeto['caducidad']) ? $objeto['caducidad'] : '',
                        "comercio" => isset($objeto['comercio']) ? $objeto['comercio'] : '',
                        "terminal" => isset($objeto['terminal']) ? $objeto['terminal'] : '',
                        "etiqueta_app" => isset($objeto['etiquetaApp']) ? $objeto['etiquetaApp'] : '',                        
                        "pedido" => isset($objeto['pedido']) ? $objeto['pedido'] : '',
                        "tipo_tasa_aplicada" => isset($objeto['tipoTasaAplicada']) ? $objeto['tipoTasaAplicada'] : '',
                        "identificador_rts" => isset($objeto['identificadorRTS']) ? $objeto['identificadorRTS'] : '',
                        "factura" => isset($objeto['factura']) ? $objeto['factura'] : '',
                        "fecha_operacion" => isset($objeto['fechaOperacion']) ? $objeto['fechaOperacion'] : '',
                        "estado" => isset($objeto['estado']) ? $objeto['estado'] : '',
                        "resultado" => isset($objeto['resultado']) ? $objeto['resultado'] : '',
                        "codigo_respuesta" => isset($objeto['codigoRespuesta']) ? $objeto['codigoRespuesta'] : '',
                        "literales" => isset($objeto['Literales']['autenticadoPorPin']) ? $objeto['Literales']['autenticadoPorPin'] : '',
                        "firma" => isset($objeto['firma']) ? $objeto['firma'] : '',
                        "operacionemv" => isset($objeto['operacionemv']) ? $objeto['operacionemv'] : '',
                        "conttrans" => isset($objeto['conttrans']) ? $objeto['conttrans'] : '',
                        "sectarjeta" => isset($objeto['sectarjeta']) ? $objeto['sectarjeta'] : '',
                        "idapp" => isset($objeto['idapp']) ? $objeto['idapp'] : '',
                        "codrespauto" => isset($objeto['codrespauto']) ? $objeto['codrespauto'] : '',
                        "resverificacion" => isset($objeto['resverificacion']) ? $objeto['resverificacion'] : '',
                        "oper_contact_less" => isset($objeto['operContactLess']) ? strtolower($objeto['operContactLess']) : '',
                        "respuesta_xml" => isset($request->respuesta_xml) ? $request->respuesta_xml : ''
                    ];


                    PagoTotem::where('id', $receptor_pago->id)->update(['estado' => $objeto['resultado']]);
                    $crear_respuesta_pago = RespuestaPago::updateOrCreate([
                        'pago_origen_id' => $request->pago_origen_id,
                    ], $data);

                    
                    $capture_xml = collect([
                        'pago_origen_id'    => $request->pago_origen_id,
                        'estado'            => $objeto['estado'],
                        'resultado'         => $objeto['resultado'],
                        'codigoRespuesta'   => $objeto['codigoRespuesta'],
                        'emisor_id'         => $receptor_pago->emisor_id,
                        'receptor_id'       => $receptor_pago->receptor_id,
                        'comercio'          => $objeto['comercio'],
                        'pedido'            => $objeto['pedido'],
                        'terminal'          => str_pad($objeto['terminal'], 3, "0", STR_PAD_LEFT),
                        'etiquetaApp'       => $objeto['etiquetaApp'] ?? '',
                        'importe'           => $objeto['importe'],
                        'tarjeta'           => $objeto['tarjetaComercioRecibo'],
                        'fecha'             => $objeto['fechaOperacion'] ,
                        'firma'             => $objeto['firma'] ?? '',
                        'Literales'         => $objeto['Literales'] ?? [],
                        'idapp'             => $objeto['idapp'] ?? '',
                        'conttrans'         => $objeto['conttrans'] ?? '',
                        'codrespauto'       => $objeto['codrespauto'] ?? '',
                        'resverificacion'   => $objeto['resverificacion'] ?? '',
                    ]);
                    
                    event(new CapturePaymentResponse($capture_xml));
                    

                    return (new RespuestaPagoResource($crear_respuesta_pago))
                        ->response()
                        ->setStatusCode(Response::HTTP_CREATED);
                } else {
                    // Error
                    $capture_xml = collect([
                        'pago_origen_id'    => $request->pago_origen_id,
                        'emisor_id'         => $receptor_pago->emisor_id,
                        'receptor_id'       => $receptor_pago->receptor_id,
                        'resultado'         => 'Error',
                        'mensaje'           => $phpArray
                    ]);

                    event(new CapturePaymentResponse($capture_xml));
                    $data = $request->all();
                    $data['resultado'] = 'Error';
                    $respuestaPago = RespuestaPago::create($data);

                    return (new RespuestaPagoResource($respuestaPago))
                        ->response()
                        ->setStatusCode(Response::HTTP_CREATED);
                }
            } else {
                return response('No se ha encontrado un receptor asociado')
                    ->setStatusCode(500);
            }

            $respuestaPago = RespuestaPago::create($request->all());

            return (new RespuestaPagoResource($respuestaPago))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response($e->getMessage())
                ->setStatusCode(500);
        }
    }

    public function show(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RespuestaPagoResource($respuestaPago->load(['pago_origen']));
    }

    public function update(UpdateRespuestaPagoRequest $request, RespuestaPago $respuestaPago)
    {
        $respuestaPago->update($request->all());

        return (new RespuestaPagoResource($respuestaPago))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(RespuestaPago $respuestaPago)
    {
        abort_if(Gate::denies('respuesta_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $respuestaPago->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
