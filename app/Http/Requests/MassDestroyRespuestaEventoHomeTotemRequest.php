<?php

namespace App\Http\Requests;

use App\Models\RespuestaEventoHomeTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyRespuestaEventoHomeTotemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('respuesta_evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:respuesta_evento_home_totems,id',
        ];
    }
}
