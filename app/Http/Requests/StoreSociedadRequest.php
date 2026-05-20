<?php

namespace App\Http\Requests;

use App\Models\Sociedad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSociedadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('sociedad_create');
    }

    public function rules()
    {
        return [
            'codigo' => [
                'string',
                'required',
                'unique:sociedads',
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
