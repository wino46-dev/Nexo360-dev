<?php

namespace App\Http\Requests;

use App\Models\DocHotelReceptionInfo;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocHotelReceptionInfoRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_hotel_reception_info_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_hotel_reception_infos,id',
        ];
    }
}
