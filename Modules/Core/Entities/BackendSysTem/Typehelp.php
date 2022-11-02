<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Typehelp extends Model
{
  	protected $connection = 'mysql_backend';  //connect database
    protected $table = 'tb_person_type_help'; //select table
    protected $primaryKey = 'person_type_help_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_type_leave_date_create";
    const UPDATED_AT = "person_type_leave_date_update";
    
        
}







