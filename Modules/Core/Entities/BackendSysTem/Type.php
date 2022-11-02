<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Type extends Model
{
  	protected $connection = 'mysql_backend';  //connect database
    protected $table = 'tb_person_type'; //select table
    protected $primaryKey = 'person_type_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_type_date_create";
    const UPDATED_AT = "person_type_date_update";
    
        
}







