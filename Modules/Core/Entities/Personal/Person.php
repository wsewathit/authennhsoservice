<?php

namespace Modules\Core\Entities\Personal;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Person extends Model
{
  	protected $connection = 'mysql_backend';
    protected $table = 'tb_person';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_date_create";
    const UPDATED_AT = "person_date_update";
    


        
}





