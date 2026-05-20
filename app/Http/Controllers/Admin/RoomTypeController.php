<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends CrudController
{
    public function __construct()
    {


        $this->setConfig([
            'id' => 'room_type',
            'url' => url('admin/rooms-types'),
            'title_singular' => 'Tipos de habitación',
            //'form_fields_required' => ['material'],
            //'import_type' => 'direct'
        ]);
    }
}
