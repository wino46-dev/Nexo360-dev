<?php

namespace App\Http\Requests;

use App\Models\DocServicioHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocServicioHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_servicio_hotel_edit');
    }

    public function rules()
    {
        return [
            'hotel_id' => [
                'required',
                'integer',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'codigo' => [
                'string',
                'nullable',
            ],
        ];
    }
}
