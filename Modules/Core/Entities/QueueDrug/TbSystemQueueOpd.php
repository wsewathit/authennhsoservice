<?php

namespace Modules\Core\Entities\QueueDrug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Entities\QueueDrug\TbSystemQueueOpdLogs;


 	


class TbSystemQueueOpd extends Model
{
  	protected $connection = 'mysql_244';
    protected $table = 'tb_system_queue_opd';
    protected $primaryKey = 'system_queue_opd_id';
    protected $fillable = [

    ];
    public static function insert_queue_opd_logs($vn,$status){
    	TbSystemQueueOpdLogs::insert([
    		'vn'=>$vn,
    		'system_queue_opd_logs_status'=>$status,
    		'system_queue_opd_date_create'=>date("Y-m-d H:i:s")
    	]);
    }
}
