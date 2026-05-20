<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateClienteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cliente_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'apellidos' => [
                'string',
                'required',
            ],
            'nif' => [
                'string',
                'required',
                'unique:clientes,nif,' . request()->route('cliente')->id,
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'pais_id' => [
                'required',
                'integer',
            ],
            'cod_postal' => [
                'string',
                'nullable',
            ],
            'direccion' => [
                'string',
                'nullable',
            ],
        ];
    }
}
