<?php

namespace App\Http\Requests;

use App\Models\Ciudad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCiudadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ciudad_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:ciudads,nombre,' . request()->route('ciudad')->id,
            ],
            'provincia_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
