<?php

namespace App\Http\Requests;

use App\Models\DocHotelReceptionInfo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDocHotelReceptionInfoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_hotel_reception_info_create');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
                'exists:establecimientos,id',
                'unique:doc_hotel_reception_infos,establecimiento_id',
            ],
            'hour_open' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'hour_close' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'open_holiday' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'close_holiday' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'box_photo' => [
                'array',
            ],
        ];
    }
}
