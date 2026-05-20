<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use Illuminate\Http\Request;

class RoomTypePriceController extends Controller
{
    public function __construct()
    {


        $this->setConfig([
            'id' => 'room-type-price',
            'url' => url('external/rooms-types-prices'),
            'title_singular' => 'Precios por tipo de habitación',
            //'form_fields_required' => ['material'],
            //'import_type' => 'direct'
        ]);
    }

    private function setConfig(array $array)
    {
    }
}
