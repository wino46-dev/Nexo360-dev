<?php

namespace App\Http\Requests;

use App\Models\Sociedad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSociedadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sociedad_edit');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
                'unique:sociedads,codigo,' . request()->route('sociedad')->id,
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'imagenes' => [
                'array',
            ],
        ];
    }
}
