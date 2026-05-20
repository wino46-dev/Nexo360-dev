<?php

namespace App\Http\Requests;

use App\Models\Habitacion;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHabitacionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('habitacion_edit');
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
                'unique:habitacions,codigo,' . request()->route('habitacion')->id,
            ],
        ];
    }
}
