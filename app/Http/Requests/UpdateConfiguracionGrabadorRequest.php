<?php

namespace App\Http\Requests;

use App\Models\ConfiguracionGrabador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConfiguracionGrabadorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuracion_grabador_edit');
    }

    public function rules()
    {
        return [
            'totem_id' => [
                'required',
                'integer',
            ],
            'software_gestion' => [
                'required',
            ],
            'reader_no' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'track_2' => [
                'string',
                'max:48',
                'nullable',
            ],
            /*'seq_mode' => [
                'string',
                'max:1',
                'required',
            ],*/

            'user_host' => [
                'string',
                'required',
            ],
            'user_port' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
