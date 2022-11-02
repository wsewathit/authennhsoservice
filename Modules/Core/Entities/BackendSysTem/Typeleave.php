<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Typeleave extends Model
{
  	protected $connection = 'mysql_backend';  //connect database
    protected $table = 'tb_person_type_leave'; //select table
    protected $primaryKey = 'person_type_leave_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_type_help_date_create";
    const UPDATED_AT = "person_type_help_date_update";
    
        
}







