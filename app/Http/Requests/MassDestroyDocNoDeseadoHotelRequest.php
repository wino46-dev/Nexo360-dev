<?php

namespace App\Http\Requests;

use App\Models\DocNoDeseadoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocNoDeseadoHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_no_deseado_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_no_deseado_hotels,id',
        ];
    }
}
