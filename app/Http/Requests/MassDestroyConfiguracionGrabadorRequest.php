<?php

namespace App\Http\Requests;

use App\Models\ConfiguracionGrabador;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyConfiguracionGrabadorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('configuracion_grabador_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:configuracion_grabadors,id',
        ];
    }
}
