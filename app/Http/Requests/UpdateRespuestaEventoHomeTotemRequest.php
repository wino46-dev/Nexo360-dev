<?php

namespace App\Http\Requests;

use App\Models\RespuestaEventoHomeTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRespuestaEventoHomeTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('respuesta_evento_home_totem_edit');
    }

    public function rules()
    {
        return [
            'evento_id' => [
                'required',
                'integer',
            ],
            'respuesta' => [
                'required',
            ],
            'estado' => [
                'required',
            ],
        ];
    }
}
