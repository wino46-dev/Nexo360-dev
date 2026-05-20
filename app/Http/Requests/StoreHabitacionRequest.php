<?php

namespace App\Http\Requests;

use App\Models\Habitacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreHabitacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('habitacion_create');
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
                'unique:habitacions',
            ],
        ];
    }
}
