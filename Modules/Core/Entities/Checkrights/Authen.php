<?php

namespace Modules\Core\Entities\Checkrights;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Authen extends Model
{
  	protected $connection = 'mysql_244_checkrights';
    protected $table = 'tb_authen';
    protected $primaryKey = 'authen_id';
    protected $fillable = [

    ];

    const CREATED_AT = "authen_date_create";
    const UPDATED_AT = "authen_date_update";
    
        
}








