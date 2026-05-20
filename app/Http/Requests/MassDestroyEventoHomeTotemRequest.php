<?php

namespace App\Http\Requests;

use App\Models\EventoHomeTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyEventoHomeTotemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('evento_home_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:evento_home_totems,id',
        ];
    }
}
