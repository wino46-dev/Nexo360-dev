<?php

namespace App\Http\Requests;

use App\Models\DocMetodoPagoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocMetodoPagoHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_metodo_pago_hotel_create');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'nombre' => [
                'string',
                'nullable',
            ],
        ];
    }
}
