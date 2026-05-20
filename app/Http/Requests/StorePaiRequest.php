<?php

namespace App\Http\Requests;

use App\Models\Pai;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePaiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pai_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:pais',
            ],
            'codigo' => [
                'string',
                'required',
                'unique:pais',
            ],
        ];
    }
}
