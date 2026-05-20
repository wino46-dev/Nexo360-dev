<?php

namespace App\Http\Requests;

use App\Models\LayoutHome;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLayoutHomeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('layout_home_edit');
    }

    public function rules()
    {
        return [
            'imagen_1' => [
                'required',
            ],
        ];
    }
}
