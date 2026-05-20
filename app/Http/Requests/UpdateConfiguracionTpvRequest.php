<?php

namespace App\Http\Requests;

use App\Models\ConfiguracionTpv;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConfiguracionTpvRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuracion_tpv_edit');
    }

    public function rules()
    {
        return [
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
