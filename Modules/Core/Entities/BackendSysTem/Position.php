<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Position extends Model
{
  	protected $connection = 'mysql_backend';
    protected $table = 'tb_person_position';
    protected $primaryKey = 'person_position_id';
    protected $fillable = [

    ];

    const CREATED_AT = "person_position_date_create";
    const UPDATED_AT = "person_position_date_update";
    
        
}







