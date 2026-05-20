<?php

namespace App\Http\Requests;

use App\Models\TipoEvento;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTipoEventoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tipo_evento_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tipo_eventos,id',
        ];
    }
}
