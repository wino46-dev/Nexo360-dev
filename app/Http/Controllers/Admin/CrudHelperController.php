<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;




class CrudHelperController  extends Controller
{


    public function index()
    {
        return view('admin.crud-helper.index');
    }

    public function action(Request $request)
    {
        $data = $request->all();
             
        
        if($data['action'] == 'fields'){
            return $this->fields($data);
        }
        //return $opc;

    }
    public function fields($data){
        
        $columns = Schema::getColumnListing($data['table']);
        $res = 'public $fieldAttributes = [
        ';
        foreach($columns as $col){
            $res .= "'$col' => [
                'type' => 'text'
            ],
            ";
        }
        $res .= '
        ];';
        return $res;

    }

}
