<?php

namespace App\Http\Requests;

use App\Models\DocInfoHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDocInfoHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('doc_info_hotel_edit');
    }

    public function rules()
    {
        $id = $this->route('doc_info_hotel');
        if (is_object($id)) { $id = $id->id; }
        return [
            'hotel_id' => [
                'required',
                'integer',
                'exists:establecimientos,id',
                'unique:doc_info_hotels,hotel_id,' . ($id ?? 'NULL') . ',id',
            ],
            'descripcion' => [
                'required',
            ],
            'direccion' => [
                'string',
                'nullable',
            ],
            'codigo_postal' => [
                'string',
                'min:5',
                'max:7',
                'nullable',
            ],
            'latitud' => [
                'string',
                'nullable',
            ],
            'longitud' => [
                'string',
                'nullable',
            ],
            'telefono' => [
                'string',
                'nullable',
            ],
            'emergencias' => [
                'string',
                'nullable',
            ],
            'web' => [
                'string',
                'nullable',
            ],
            'enlace_fotos' => [
                'string',
                'nullable',
            ],
            'cuenta_bancaria' => [
                'string',
                'nullable',
            ],
            'modos_cobro' => [
                'string',
                'nullable',
            ],
        ];
    }
}
