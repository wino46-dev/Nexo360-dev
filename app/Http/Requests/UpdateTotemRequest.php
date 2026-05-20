<?php

namespace App\Http\Requests;

use App\Models\Totem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('totem_edit');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
            'codigo' => [
                'string',
                'required',
                'unique:totems,codigo,' . request()->route('totem')->id,
            ],
            'fuente_imagenes' => [
                'required',
            ],
            'imagenes' => [
                'array',
            ],
        ];
    }
}
