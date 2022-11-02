<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class TbSystemQueueOpdLogs extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_queue_opd_logs';
    protected $primaryKey = 'system_queue_opd_logs_id';
    protected $fillable = [

    ];
}
