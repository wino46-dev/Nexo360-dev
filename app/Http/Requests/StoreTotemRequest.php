<?php

namespace App\Http\Requests;

use App\Models\Totem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('totem_create');
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
                'unique:totems',
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
