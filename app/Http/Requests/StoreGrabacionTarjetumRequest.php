<?php

namespace App\Http\Requests;

use App\Models\GrabacionTarjetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreGrabacionTarjetumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('grabacion_tarjetum_create');
    }

    public function rules()
    {
        return [
            'emisor_id' => [
                'required',
                'integer',
            ],
            'receptor_id' => [
                'required',
                'integer',
            ],
            'date_in' => [
                'required',
                //'date_format:' . config('panel.date_format'),
            ],
            'time_in' => [
                'required',
                //'date_format:' . config('panel.time_format'),
            ],
            'date_out' => [
                'required',
                //'date_format:' . config('panel.date_format'),
            ],
            'time_out' => [
                'required',
                //'date_format:' . config('panel.time_format'),
            ],
            'room_no' => [
                'required'
            ],
            'room_no_2' => [
                'nullable'
            ],
            'room_no_3' => [
                'nullable'
            ],
            'safe_box' => [
                'string',
                'min:1',
                'max:1',
                'required',
            ],
            'card_qty' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'uid_card' => [
                'string',
                'required',
            ],
            'room_no' => [
                'required'
            ],
        ];
    }
}
