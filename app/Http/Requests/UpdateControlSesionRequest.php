<?php

namespace App\Http\Requests;

use App\Models\ControlSesion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateControlSesionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('control_sesion_edit');
    }

    public function rules()
    {
        return [
            'emisor_id' => [
                'required',
                'integer',
            ],
            'receptor_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
