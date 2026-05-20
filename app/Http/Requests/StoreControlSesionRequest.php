<?php

namespace App\Http\Requests;

use App\Models\ControlSesion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreControlSesionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('control_sesion_create');
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
