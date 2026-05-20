<?php

namespace App\Http\Requests;

use App\Models\RespuestaPago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRespuestaPagoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('respuesta_pago_edit');
    }

    public function rules()
    {
        return [
            'pago_origen_id' => [
                'required',
                'integer',
            ],
            'tipo_pago' => [
                'string',
                'nullable',
            ],
            'tipo_oper' => [
                'string',
                'nullable',
            ],
            'moneda' => [
                'string',
                'nullable',
            ],
            'tarjeta_comercio_recibo' => [
                'string',
                'nullable',
            ],
            'tarjeta_cliente_recibo' => [
                'string',
                'nullable',
            ],
            'caducidad' => [
                'string',
                'nullable',
            ],
            'comercio' => [
                'string',
                'nullable',
            ],
            'terminal' => [
                'string',
                'nullable',
            ],
            'tarjeta' => [
                'string',
                'nullable',
            ],
            'identificador_rts_base' => [
                'string',
                'nullable',
            ],
            'pedido' => [
                'string',
                'nullable',
            ],
            'tipo_tasa_aplicada' => [
                'string',
                'nullable',
            ],
            'identificador_rts' => [
                'string',
                'nullable',
            ],
            'factura' => [
                'string',
                'nullable',
            ],
            'fecha_operacion' => [
                'string',
                'nullable',
            ],
            'estado' => [
                'string',
                'nullable',
            ],
            'resultado' => [
                'string',
                'nullable',
            ],
            'codigo_respuesta' => [
                'string',
                'nullable',
            ],
            'operacionemv' => [
                'string',
                'nullable',
            ],
            'conttrans' => [
                'string',
                'nullable',
            ],
            'sectarjeta' => [
                'string',
                'nullable',
            ],
            'idapp' => [
                'string',
                'nullable',
            ],
            'codrespauto' => [
                'string',
                'nullable',
            ],
            'resverificacion' => [
                'string',
                'nullable',
            ],
            'version' => [
                'string',
                'nullable',
            ],
        ];
    }
}
