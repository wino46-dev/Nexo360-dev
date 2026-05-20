<?php

namespace App\Http\Requests;

use App\Models\Establecimiento;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEstablecimientoRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('establecimiento_create');
    }

    public function rules()
    {
        return [
            'sociedad_id' => [
                'required',
                'integer',
            ],
            'codigo' => [
                'string',
                'required',
                'unique:establecimientos',
            ],
            'nombre' => [
                'string',
                'required',
            ],
            'api_pms' => [
                'nullable',
                'in:local,roomdoo,misterplan',
            ],
            'api_pms_url' => [
                'nullable',
                'string',
                'required_if:api_pms,roomdoo,misterplan',
            ],
            'api_pms_username' => [
                'nullable',
                'string',
                'required_if:api_pms,roomdoo',
            ],
            'api_pms_password' => [
                'nullable',
                'string',
                'required_if:api_pms,roomdoo',
            ],
            'remote_hotel_id' => [
                'nullable',
                'string',
                'required_if:api_pms,misterplan,roomdoo',
            ],
            'misterplan_api_key' => [
                'nullable',
                'string',
            ],
            'misterplan_channel_id' => [
                'nullable',
                'string',
                'required_if:api_pms,misterplan',
            ],
            'extensiones' => [
                'nullable',
                'string',
            ],
            'imagenes' => [
                'array',
            ],
            'tour_images' => [
                'array',
            ],
        ];
    }
}
