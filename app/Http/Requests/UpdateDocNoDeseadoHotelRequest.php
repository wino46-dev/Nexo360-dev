<?php

namespace App\Http\Requests;

use App\Models\DocNoDeseadoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocNoDeseadoHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_no_deseado_hotel_edit');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'fecha' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'datos_cliente' => [
                'required',
            ],
            'motivo' => [
                'string',
                'required',
            ],
        ];
    }
}
