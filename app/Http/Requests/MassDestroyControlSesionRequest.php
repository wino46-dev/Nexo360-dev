<?php

namespace App\Http\Requests;

use App\Models\ControlSesion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyControlSesionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('control_sesion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:control_sesions,id',
        ];
    }
}
