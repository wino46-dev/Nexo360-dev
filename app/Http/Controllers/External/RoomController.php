<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends CrudController
{

    public function __construct()
    {
        parent::__construct(Room::class);

        $this->setConfig([
            'id' => 'room',
            'url' => url('external/rooms'),
            'title_singular' => 'Habitacion',
            //'form_fields_required' => ['material'],
            //'import_type' => 'direct'
        ]);
    }
}
