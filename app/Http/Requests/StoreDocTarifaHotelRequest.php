<?php

namespace App\Http\Requests;

use App\Models\DocTarifaHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocTarifaHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_tarifa_hotel_create');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'tarifa' => [
                'string',
                'required',
            ],
            'importe' => [
                'required',
            ],
            'fecha' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
