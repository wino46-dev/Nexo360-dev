<?php

namespace App\Http\Requests;

use App\Models\ParteViajero;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreParteViajeroRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('parte_viajero_create');
    }

    public function rules()
    {
        return [
            'reserva_id' => [
                'required',
                'integer',
            ],
            'cliente_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
