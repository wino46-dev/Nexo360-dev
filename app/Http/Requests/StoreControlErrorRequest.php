<?php

namespace App\Http\Requests;

use App\Models\ControlError;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreControlErrorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        //Gate::allows('control_error_create');
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
