<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use Illuminate\Http\Request;

class RoomTypePriceController extends Controller
{
    public function __construct()
    {
        parent::__construct(RoomTypePrice::class);

        $this->setConfig([
            'id' => 'room-type-price',
            'url' => url('admin/rooms-types-prices'),
            'title_singular' => 'Precios por tipo de habitación',
            //'form_fields_required' => ['material'],
            //'import_type' => 'direct'
        ]);
    }
}
