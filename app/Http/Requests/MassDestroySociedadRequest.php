<?php

namespace App\Http\Requests;

use App\Models\Sociedad;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySociedadRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('sociedad_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:sociedads,id',
        ];
    }
}
