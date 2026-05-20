<?php

namespace App\Http\Requests;

use App\Models\DocHabitacionHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocHabitacionHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_habitacion_hotel_create');
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
            'capacidad' => [
                'required',
            ],
            'codigo' => [
                'string',
                'nullable',
            ],
        ];
    }
}
