<?php

namespace Modules\Core\Entities\Healthcheck;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class TbHealthCheckList extends Model
{
  	protected $connection = 'mysql_healthcheck';
    protected $table = 'tb_healthcheck_list';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];
}





