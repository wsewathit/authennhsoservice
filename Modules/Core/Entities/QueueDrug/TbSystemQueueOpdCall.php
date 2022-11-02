<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\QueueDrug\TbSystemQueueOpdLogs;


 	


class TbSystemQueueOpdCall extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_queue_opd_call';
    protected $primaryKey = 'system_queue_opd_call_id';
    protected $fillable = [

    ];
}



