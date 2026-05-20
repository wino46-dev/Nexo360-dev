<?php

namespace App\Http\Requests;

use App\Models\DocHotelEstadoCaja;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocHotelEstadoCajaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_hotel_estado_caja_edit');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'caja' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'code' => [
                'string',
                'nullable',
            ],
            'cliente' => [
                'string',
                'nullable',
            ],
            'documento_cliente' => [
                'string',
                'nullable',
            ],
            'fecha' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
