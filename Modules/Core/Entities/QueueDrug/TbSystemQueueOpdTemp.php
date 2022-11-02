<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class TbSystemQueueOpdTemp extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_queue_opd_temp';
    protected $primaryKey = 'system_queue_opd_temp_id';
    protected $fillable = [

    ];
}
