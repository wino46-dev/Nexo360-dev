<?php

namespace App\Http\Requests;

use App\Models\DocIncidenciaHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocIncidenciaHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_incidencia_hotel_edit');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'fecha' => [
                'nullable',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'titulo' => [
                'string',
                'required',
            ],
            'estado' => [
                'required',
            ],
            'dni' => [
                'string',
                'nullable',
            ],
            'nombre' => [
                'string',
                'nullable',
            ],
        ];
    }
}
