<?php

namespace App\Http\Requests;

use App\Models\TipoEvento;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTipoEventoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tipo_evento_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:tipo_eventos,nombre,' . request()->route('tipo_evento')->id,
            ],
        ];
    }
}
