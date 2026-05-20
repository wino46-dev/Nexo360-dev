<?php

namespace App\Http\Requests;

use App\Models\FirmaCheckIn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFirmaCheckInRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('firma_check_in_create');
    }

    public function rules()
    {
        return [
            'sesion_id' => [
                'required',
                'integer',
            ]
        ];
    }
}
