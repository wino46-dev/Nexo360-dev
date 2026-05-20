<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudModel extends Model
{

    protected static function newModelInstance(): Model
    {
        return new static();
    }


    public static function tableColumns()
    {        
        $model = static::newModelInstance();
        $fields = $model->getFillable();
        $fields = array_combine($fields, $fields);
        unset($fields['created_at']);
        unset($fields['updated_at']);
        unset($fields['deleted_at']);
        
        foreach($fields as $k => $row){
            $fields[$k] = [                
                'class'=> 'editable'
            ];
        }
        return $fields;
    }

    public static function formFields()
    {        
        $model = static::newModelInstance();
        $fields = $model->getFillable();
        $fields = array_combine($fields, $fields);
        unset($fields['created_at']);
        unset($fields['updated_at']);
        unset($fields['deleted_at']);
        
        foreach($fields as $k => $row){
            $fields[$k] = [                
                'type'=> 'text'
            ];
        }
        return $fields;
    }


    
}