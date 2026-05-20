<?php

namespace App\Http\Requests;

use App\Models\ControlError;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateControlErrorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('control_error_edit');
    }

    public function rules()
    {
        return [
            'origen' => [
                'string',
                'required',
            ],
            'tipo' => [
                'string',
                'required',
            ],
            'mensaje' => [
                'string',
                'required',
            ],
        ];
    }
}
