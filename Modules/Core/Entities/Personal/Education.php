<?php

namespace Modules\Core\Entities\Personal;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Education extends Model
{
  	protected $connection = 'mysql_backend';
    protected $table = 'tb_education';
    protected $primaryKey = 'person_education_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_education_date_create";
    const UPDATED_AT = "person_education_date_update";
    


        
}





