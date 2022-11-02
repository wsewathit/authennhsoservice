<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Education extends Model
{
  	protected $connection = 'mysql_backend';
    protected $table = 'tb_person_education';
    protected $primaryKey = 'person_education_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_education_date_create";
    const UPDATED_AT = "person_education_date_update";
    
        
}







