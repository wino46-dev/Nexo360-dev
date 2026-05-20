<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreClienteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cliente_create');
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
                'unique:clientes',
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
