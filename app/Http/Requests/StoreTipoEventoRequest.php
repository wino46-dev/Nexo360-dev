<?php

namespace App\Http\Requests;

use App\Models\TipoEvento;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTipoEventoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tipo_evento_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:tipo_eventos',
            ],
        ];
    }
}
