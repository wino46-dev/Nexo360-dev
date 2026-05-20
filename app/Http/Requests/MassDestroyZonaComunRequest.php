<?php

namespace App\Http\Requests;

use App\Models\ZonaComun;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyZonaComunRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('zona_comun_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:zona_comuns,id',
        ];
    }
}