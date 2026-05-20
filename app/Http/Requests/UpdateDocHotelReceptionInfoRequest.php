<?php

namespace App\Http\Requests;

use App\Models\DocHotelReceptionInfo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocHotelReceptionInfoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_hotel_reception_info_edit');
    }

    public function rules()
    {
        $id = $this->route('doc_hotel_reception_info');
        if (is_object($id)) { $id = $id->id; }
        return [
            'establecimiento_id' => [
                'required',
                'integer',
                'exists:establecimientos,id',
                'unique:doc_hotel_reception_infos,establecimiento_id,' . ($id ?? 'NULL') . ',id',
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
