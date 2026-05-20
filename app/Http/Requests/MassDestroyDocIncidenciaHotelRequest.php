<?php

namespace App\Http\Requests;

use App\Models\DocIncidenciaHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocIncidenciaHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_incidencia_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_incidencia_hotels,id',
        ];
    }
}
