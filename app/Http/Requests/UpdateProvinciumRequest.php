<?php

namespace App\Http\Requests;

use App\Models\Provincium;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProvinciumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('provincium_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
                'unique:provincia,nombre,' . request()->route('provincium')->id,
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
