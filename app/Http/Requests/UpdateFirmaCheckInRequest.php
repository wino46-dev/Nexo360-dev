<?php

namespace App\Http\Requests;

use App\Models\FirmaCheckIn;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateFirmaCheckInRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('firma_check_in_edit');
    }

    public function rules()
    {
        return [
            'documento' => [
                'required',
            ],
        ];
    }
}
