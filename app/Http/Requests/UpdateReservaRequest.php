<?php

namespace App\Http\Requests;

use App\Models\Reserva;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateReservaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('reserva_edit');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'codigo' => [
                'string',
                'required',
                'unique:reservas,codigo,' . request()->route('reserva')->id,
            ],
            'cliente_id' => [
                'required',
                'integer',
            ],
            'entrada' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'salida' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'huespedes' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'total_reserva' => [
                'required',
            ],
        ];
    }
}
