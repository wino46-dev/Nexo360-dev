<?php

namespace App\Http\Requests;

use App\Models\ConfiguracionTpv;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreConfiguracionTpvRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuracion_tpv_create');
    }

    public function rules()
    {
        return [
            'totem_id' => [
                'required',
                'integer',
            ],
            'comercio' => [
                'string',
                'required',
            ],
            'terminal' => [
                'string',
                'required',
            ],
            'clave_firma' => [
                'string',
                'required',
            ],
            'conf_puerto' => [
                'string',
                'required',
            ],
            'version' => [
                'string',
                'required',
            ],
        ];
    }
}
