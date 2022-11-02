<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class TbSystemOrderQueueNotifyCall extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_order_queue_notify_call';
    protected $primaryKey = 'system_order_queue_notify_call_id';
    protected $fillable = [

    ];
}
