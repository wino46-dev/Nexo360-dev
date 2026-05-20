<?php

namespace App\Http\Requests;

use App\Models\EventoHomeTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEventoHomeTotemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('evento_home_totem_create');
    }

    public function rules()
    {
        return [
            'receptor_id' => [
                'required',
                'integer',
            ],
            'tipo_evento_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
