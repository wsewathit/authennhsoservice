<?php

namespace Modules\Core\Entities\Healthcheck;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class TbHealthCheckDetail extends Model
{
  	protected $connection = 'mysql_healthcheck';
    protected $table = 'tb_healthcheck_detail';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    const CREATED_AT = "healthcheck_result_date_create";
    const UPDATED_AT = "healthcheck_result_date_update";
    
        
}





