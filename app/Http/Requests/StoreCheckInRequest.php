<?php

namespace App\Http\Requests;

use App\Models\CheckIn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCheckInRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('check_in_create');
    }

    public function rules()
    {
        return [
            'cliente_id' => [
                'required',
                'integer',
            ],
            'reserva_id' => [
                'required',
                'integer',
            ],
            'totem_id' => [
                'required',
                'integer',
            ],
            'llave' => [
                'string',
                'nullable',
            ],
        ];
    }
}
