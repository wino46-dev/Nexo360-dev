<?php

namespace App\Http\Requests;

use App\Models\DockStockHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDockStockHotelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('dock_stock_hotel_edit');
    }

    public function rules()
    {
        return [
            'nombre' => [
                'string',
                'required',
            ],
            'cantidad' => [
                'numeric',
            ],
        ];
    }
}
