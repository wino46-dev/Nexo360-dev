<?php

namespace App\Http\Requests;

use App\Models\ParteViajero;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyParteViajeroRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('parte_viajero_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:parte_viajeros,id',
        ];
    }
}
