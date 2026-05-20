<?php

namespace App\Http\Requests;

use App\Models\DockStockHotel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDockStockHotelRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('dock_stock_hotel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:dock_stock_hotels,id',
        ];
    }
}
