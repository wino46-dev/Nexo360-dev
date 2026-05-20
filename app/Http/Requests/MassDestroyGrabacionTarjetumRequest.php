<?php

namespace App\Http\Requests;

use App\Models\GrabacionTarjetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyGrabacionTarjetumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('grabacion_tarjetum_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:grabacion_tarjeta,id',
        ];
    }
}
