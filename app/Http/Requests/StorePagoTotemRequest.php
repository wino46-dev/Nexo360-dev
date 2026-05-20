<?php

namespace App\Http\Requests;

use App\Models\PagoTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePagoTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pago_totem_create');
    }

    public function rules()
    {
        return [
            'emisor_id' => [
                'required',
                'integer',
            ],
            'receptor_id' => [
                'required',
                'integer',
            ],
            'importe' => [
                'required',
            ],
            'factura' => [
                'string',
                'required',
            ],
        ];
    }
}
