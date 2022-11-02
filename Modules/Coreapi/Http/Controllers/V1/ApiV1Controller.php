<?php

namespace Modules\Coreapi\Http\Controllers\V1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Core\Entities\QueueDrug\TbSystemQueueAuthen;
use Modules\Quedrug\Entities\Ovst;
use Modules\Quedrug\Entities\Patient;
use DB;

class ApiV1Controller extends Controller
{
    
    public function get_patient($cid)
    {

       


        $connect_his = DB::connection('his_readonly');    	
		$sql = "SELECT cid,REPLACE(hometel,'-','') AS phone FROM patient WHERE cid = '$cid'";	
		$data_his_set = $connect_his->select($sql);
		if($data_his_set != null){
			$data = ['status'=>true,'data'=>$data_his_set,'checknhso_status'=> StatusCheckNhso ];
		}else{
			$data = ['status'=>false];
		}
		return $data;
    }
    public function post_authen(Request $request)
    {
        $GetPatient = Patient::where('cid',$request->cid)->select('hn','pname','fname','lname')->first();
        $data = [];
        if($GetPatient != null){
            $data['patient'] = $GetPatient;
            $GetOvst = Ovst::where('hn',$GetPatient->hn)->where('vstdate',date("Y-m-d"))
            ->join('kskdepartment', function ($join) {
             $join->on('kskdepartment.depcode', '=', 'ovst.main_dep');
            })->select('department','vn','main_dep');
            if($GetOvst->count() > 0){
                 $data['ovst'] = $GetOvst->get()->toArray();
                 $data['status'] = true;
            }else{
                  $data['status'] = true;
            }
        }else{
             $data['status'] = true;
        }

        TbSystemQueueAuthen::insert([
			"authenval" => $request->authenval,
			"cid" => $request->cid,
			"phone" => $request->phone,
			"correlationId" => $request->correlationId,
			'created_at'=>date("Y-m-d H:i:s")
        ]);
        return ['status' => true,'data'=>$data];
    }

    public function print_queue($vn = null){
            $data['hn'] = "9999999";
            $data['vn'] = "651025060058";
            $data['qn'] = "009999";
            $data['queue_text'] = "B998";
            $data['link_q'] = null;
            $data['line_a_01'] = "<div align='center' style='font-size:14px;font-weight:bold;'> ( <u>สำหรับดูคิวออนไลน์</u> ) </div>";
            $data['line_a_02'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_03'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_04'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_05'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_06'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_07'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_08'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_09'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_10'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_a_11'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
           $data['line_b_01'] = "<div align='center' style='font-size:14px;font-weight:bold;'> ( <u>สำหรับดูคิวออนไลน์</u> ) </div>";
            $data['line_b_02'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_03'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_04'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_05'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_06'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_07'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_08'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_09'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_10'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_11'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_12'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_13'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_14'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_15'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_16'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            $data['line_b_17'] =  "<div align='right'  style='font-size:12px;font-weight: bold;'> [ x ] SCAN OPD Card แล้ว </div>";
            return $data;
    }
	    

}