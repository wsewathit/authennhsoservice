<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Person extends Model
{
  	protected $connection = 'mysql_backend';  //connect database
    protected $table = 'tb_person'; //select table
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_date_create";
    const UPDATED_AT = "person_date_update";
    
        
}







