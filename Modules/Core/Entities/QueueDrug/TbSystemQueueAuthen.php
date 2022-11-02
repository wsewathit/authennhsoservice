<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class TbSystemQueueAuthen extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_queue_authen';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];
}





