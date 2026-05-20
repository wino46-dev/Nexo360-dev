<?php

namespace App\Http\Requests;

use App\Models\GrabacionTarjetum;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateGrabacionTarjetumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('grabacion_tarjetum_edit');
    }

    public function rules()
    {
        return [
            'uid_card' => [
                'string',
                'required',
            ],
        ];
    }
}
