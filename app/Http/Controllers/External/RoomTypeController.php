<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends CrudController
{
    public function __construct()
    {
        parent::__construct(RoomType::class);

        $this->setConfig([
            'id' => 'room_type',
            'url' => url('external/rooms-types'),
            'title_singular' => 'Tipos de habitación',
            //'form_fields_required' => ['material'],
            //'import_type' => 'direct'
        ]);
    }
}
