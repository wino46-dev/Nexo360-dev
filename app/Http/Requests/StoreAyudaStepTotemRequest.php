<?php

namespace App\Http\Requests;

use App\Models\AyudaStepTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAyudaStepTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ayuda_step_totem_create');
    }

    public function rules()
    {
        return [
            'establecimiento_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
