<?php

namespace App\Http\Requests;

use App\Models\Pai;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePaiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pai_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:pais,nombre,' . request()->route('pai')->id,
            ],
            'codigo' => [
                'string',
                'required',
                'unique:pais,codigo,' . request()->route('pai')->id,
            ],
        ];
    }
}
