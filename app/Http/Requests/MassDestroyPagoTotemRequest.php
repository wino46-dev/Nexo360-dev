<?php

namespace App\Http\Requests;

use App\Models\PagoTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPagoTotemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pago_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:pago_totems,id',
        ];
    }
}
