<?php

namespace App\Http\Requests;

use App\Models\Totem;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTotemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('totem_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:totems,id',
        ];
    }
}
