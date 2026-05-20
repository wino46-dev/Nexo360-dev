<?php

namespace App\Http\Requests;

use App\Models\AyudaStepTotem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAyudaStepTotemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ayuda_step_totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ayuda_step_totems,id',
        ];
    }
}
