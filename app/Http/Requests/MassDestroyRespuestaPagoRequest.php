<?php

namespace App\Http\Requests;

use App\Models\RespuestaPago;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRespuestaPagoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('respuesta_pago_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:respuesta_pagos,id',
        ];
    }
}
