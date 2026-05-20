<?php

namespace App\Http\Requests;

use App\Models\DocTarifaHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocTarifaHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_tarifa_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_tarifa_hotels,id',
        ];
    }
}
