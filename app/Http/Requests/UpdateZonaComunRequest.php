<?php

namespace App\Http\Requests;

use App\Models\ZonaComun;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateZonaComunRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('zona_comun_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'codigo' => [
                'string',
                'nullable',
            ],
            'establecimiento_id' => [
                'required',
                'integer',
            ],
        ];
    }
}