<?php

namespace App\Http\Requests;

use App\Models\DocMetodoPagoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDocMetodoPagoHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('doc_metodo_pago_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:doc_metodo_pago_hotels,id',
        ];
    }
}
