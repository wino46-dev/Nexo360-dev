<?php

namespace App\Http\Requests;

use App\Models\DocHotelEstadoCaja;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocHotelEstadoCajaRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_hotel_estado_caja_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_hotel_estado_cajas,id',
        ];
    }
}
