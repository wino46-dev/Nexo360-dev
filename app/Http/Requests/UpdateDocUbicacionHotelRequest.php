<?php

namespace App\Http\Requests;

use App\Models\DocUbicacionHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocUbicacionHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_ubicacion_hotel_edit');
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
                'required',
            ],
            'piso' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
