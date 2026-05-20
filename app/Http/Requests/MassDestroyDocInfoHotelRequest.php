<?php

namespace App\Http\Requests;

use App\Models\DocInfoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocInfoHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_info_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_info_hotels,id',
        ];
    }
}
