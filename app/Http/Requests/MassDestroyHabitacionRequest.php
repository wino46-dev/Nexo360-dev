<?php

namespace App\Http\Requests;

use App\Models\Habitacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyHabitacionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('habitacion_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:habitacions,id',
        ];
    }
}
