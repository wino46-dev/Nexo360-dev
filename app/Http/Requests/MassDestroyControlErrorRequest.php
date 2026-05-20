<?php

namespace App\Http\Requests;

use App\Models\ControlError;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyControlErrorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('control_error_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:control_errors,id',
        ];
    }
}
