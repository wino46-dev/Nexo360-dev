<?php

namespace App\Http\Requests;

use App\Models\Provincium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProvinciumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('provincium_create');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:provincia',
            ],
            'pais_id' => [
                'required',
                'integer',
            ],
            'iso' => [
                'string',
                'nullable',
            ],
        ];
    }
}
