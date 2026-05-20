<?php

namespace App\Http\Requests;

use App\Models\Ciudad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCiudadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ciudad_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:ciudads',
            ],
            'provincia_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
