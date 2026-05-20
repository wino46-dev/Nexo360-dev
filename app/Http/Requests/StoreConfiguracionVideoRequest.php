<?php

namespace App\Http\Requests;

use App\Models\ConfiguracionVideo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreConfiguracionVideoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuracion_video_create');
    }

    public function rules()
    {
        return [
            'totem_id' => [
                'required',
                'integer',
            ],
            'sip_identity' => [
                'string',
                'required',
            ],
            'display_name' => [
                'string',
                'required',
            ],
            'sip_registar' => [
                'string',
                'required',
            ],
            'username' => [
                'string',
                'required',
            ],
            'password' => [
                'string',
                'required',
            ],
            'sip_identity_destino' => [
                'string',
                'nullable',
            ],
        ];
    }
}
