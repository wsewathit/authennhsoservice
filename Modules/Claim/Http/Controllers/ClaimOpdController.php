<?php

namespace Modules\Claim\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

use DB;
class ClaimOpdController extends Controller
{

    public function index()
    {
        return view('claim::index');
    }
    public function get_data(){
        set_time_limit(0);

        $datestart = '2022-10-01';
        $dateend = '2022-10-31';


        $connect_his = DB::connection('his_readonly');
        $data_his_set = $connect_his->select("SELECT /*CONCAT(o.vstdate,' ',o.vsttime) AS vstdate,p.cid,o.vn,o.hn,o.pttype,pttype.name as ptname,CONCAT(p.pname,'',p.fname,' ',p.lname) AS fullname,od.icd10 AS pdx , main_dep ,CONCAT(k.department) AS department,(SELECT sum(sum_price)  FROM opitemrece WHERE vn = o.vn LIMIT 1) AS sumopitemrece */  od.icd10 AS pdx,o.vn FROM ovst AS o LEFT OUTER JOIN pttype ON pttype.pttype = o.pttype LEFT OUTER JOIN patient AS p ON p.hn = o.hn LEFT OUTER JOIN kskdepartment AS k ON k.depcode = o.main_dep LEFT OUTER JOIN ovstdiag AS od ON od.vn=o.vn WHERE ( o.vstdate BETWEEN '$datestart' AND  '$dateend' )  AND  o.an IS NULL AND od.diagtype = '1' AND pttype.pcode IN ('AE','AF','AB','AG','AA','AD','AC','AJ','AK','AH','UC') AND o.main_dep <> '035'");

       
        if($data_his_set != null){
            $key = 0;
            $data_his = [];
            foreach ($data_his_set as $keyloop => $rs) {
                $get_query_income_2 = $connect_his->select("SELECT nd.billcode AS icode,opi.qty,opi.unitprice,opi.sum_price,nd.name AS ndname FROM opitemrece AS opi LEFT OUTER JOIN nondrugitems AS nd ON nd.icode=opi.icode WHERE opi.vn = '$rs->vn' AND opi.income = '02' ORDER BY opi.pttype AND opi.qty > 0");

                $get_query_fp = $connect_his->select("SELECT opi.vn,
                    IF((SELECT COUNT(ovstdiag.vn) FROM ovstdiag WHERE ovstdiag.vn = vns.vn AND icd10  IN ('Z301') ) > 0,'Z301',
                    IF((SELECT COUNT(ovstdiag.vn) FROM ovstdiag WHERE ovstdiag.vn = vns.vn AND icd10  IN ('Z308') ) > 0,'Z308','')) AS  counticd10 ,
                    IF((SELECT COUNT(ovstdiag.vn) FROM ovstdiag WHERE ovstdiag.vn = vns.vn AND icd10  IN ('697') ) > 0 ,'697',
                    IF((SELECT COUNT(ovstdiag.vn) FROM ovstdiag WHERE ovstdiag.vn = vns.vn AND icd10  IN ('9923') ) > 0 ,'9923','')) AS   counticd9
                    FROM opitemrece AS opi
                    LEFT OUTER JOIN vn_stat AS vns ON vns.vn = opi.vn 
                    WHERE opi.vn = '$rs->vn' AND 
                    opi.icode IN ('3335116','3335115','3335114','1580063','1620056')  
                    AND ((SELECT SUM(qty) FROM opitemrece WHERE opitemrece.icode in ( '1580063','1620056') AND  opitemrece.vn = opi.vn ) > 0) ");

                $get_query_income_07 = $connect_his->select("
                        SELECT  nd.billcode AS icode,nd.`name` AS ndname,price,qty FROM opitemrece AS o LEFT OUTER JOIN nondrugitems AS nd ON nd.icode = o.icode WHERE ( vn = '$rs->vn' AND o.income = '07' AND o.qty > 0)
                        UNION 
                        SELECT nd.billcode AS icode,nd.`name` AS ndname, price,qty FROM opitemrece AS o LEFT OUTER JOIN nondrugitems AS nd ON nd.icode = o.icode WHERE (vn = '$rs->vn' AND o.icode IN (
                        '3335932','3335933','3336514','3336515'
                        ) AND o.qty > 0 )");

                $get_query_income_07_covid = $connect_his->select("
                        SELECT nd.billcode AS icode,nd.`name` AS ndname, price,qty FROM opitemrece AS o LEFT OUTER JOIN nondrugitems AS nd ON nd.icode = o.icode WHERE (vn = '$rs->vn' AND o.icode IN (
                        '3335932','3335933','3336514','3336515','3335930'
                        ) AND o.qty > 0 ) LIMIT 1");
                
                $get_query_morphine = $connect_his->select("
                        SELECT  nd.icode AS icode,nd.`name` AS ndname,nd.unitprice AS price,qty FROM opitemrece AS o LEFT OUTER JOIN drugitems AS nd ON nd.icode = o.icode WHERE o.icode IN ('1490543','1600038','1600039') AND ( vn = '$rs->vn' AND o.qty > 0)");
                
                $get_query_tamox = $connect_his->select("SELECT  nd.icode AS icode,nd.`name` AS ndname,nd.unitprice AS price,qty FROM opitemrece AS o LEFT OUTER JOIN drugitems AS nd ON nd.icode = o.icode WHERE o.icode IN ('1490163') AND ( vn = '$rs->vn' AND o.qty > 0)");


                $get_query_chemo = $connect_his->select("
                        SELECT  nd.icode AS icode,nd.`name` AS ndname,nd.unitprice AS price,qty FROM opitemrece AS o LEFT OUTER JOIN drugitems AS nd ON nd.icode = o.icode WHERE o.icode IN ('1520003','1580041','1580053','1500034','1480301','1550046','1590003','1580040','1600031') AND ( vn = '$rs->vn' AND o.qty > 0)");
                
                $get_query_refer = $connect_his->select("SELECT referout_id FROM referout WHERE vn ='$rs->vn' LIMIT 1");

                // get_query_er get_query_er_oprefer


                $get_query_er = $connect_his->select("SELECT o.vn FROM ovst AS o LEFT OUTER JOIN vn_stat AS vns ON vns.vn = o.vn LEFT OUTER JOIN opitemrece AS opi ON opi.vn=o.vn WHERE o.vn = '$rs->vn' AND ((o.pttype IN ('53','54','91','92','93','94','95','96','97','98','99') AND o.ovstist = '01' AND vns.income <> 0 AND o.an IS NULL) OR (opi.icode IN ('1570049') AND opi.qty > 0))  GROUP BY o.vn");
            

                $get_query_er_oprefer = $connect_his->select("SELECT o.vn FROM ovst AS o LEFT OUTER JOIN vn_stat AS vns ON vns.vn = o.vn WHERE o.vn = '$rs->vn' AND o.pttype IN ('53','54','91','92','93','94','95','96','97','98','99'/*,'64','65','68','74','84'*/) AND o.ovstist IN ('02','04') AND vns.income <> 0 AND o.an IS NULL");

                $get_query_clopidogrel = $connect_his->select("
                        SELECT  nd.icode AS icode,nd.`name` AS ndname,nd.unitprice AS price,qty FROM opitemrece AS o LEFT OUTER JOIN drugitems AS nd ON nd.icode = o.icode WHERE o.icode IN ('1511201') AND ( vn = '$rs->vn' AND o.qty > 0)");

                $get_query_dent_child = $connect_his->select("SELECT o.vn FROM ovst AS o LEFT OUTER JOIN dtmain AS dtmn ON dtmn.vn=o.vn LEFT OUTER JOIN dttm AS dttm ON  dttm.`code`=dtmn.tmcode LEFT OUTER JOIN vn_stat AS vns ON vns.vn=o.vn WHERE o.vn = '$rs->vn'    AND ( vns.age_y BETWEEN '4'AND '12' ) AND dttm.icd10tm_operation_code  IN ('2377020','2377021')");


                $get_query_dent_child_2 = $connect_his->select("SELECT o.vn FROM ovst AS o LEFT OUTER JOIN dtmain AS dtmn ON dtmn.vn=o.vn LEFT OUTER JOIN dttm AS dttm ON  dttm.`code`=dtmn.tmcode LEFT OUTER JOIN vn_stat AS vns ON vns.vn=o.vn WHERE o.vn = '$rs->vn'    AND  ( vns.age_y BETWEEN '6' AND '12' ) AND dttm.icd10tm_operation_code IN ('238703A','238703B','238703C','238703D','238703E','238703F','238703G','238703H')");

                $get_opsi = $connect_his->select(DB::raw("SELECT vn FROM ovstdiag WHERE icd10 IN ('U071','U072') AND vn = '$rs->vn' LIMIT 1"));

                $get_opae = $connect_his->select(DB::raw("SELECT vn FROM ovst WHERE vn = '$rs->vn' AND main_dep = '130'  AND pttype IN ('13','14','19','32','33','40','41','42','50','51','52','53','54','60','61','62','63','64','68','81','82','83','84','85','86','87','88','89','91','92','93','94','95','96','97','98','99')   LIMIT 1"));


                $get_query_odsmis = $connect_his->select("SELECT h.vn,ifnull((SELECT o.hn FROM ovst o where o.vn=h.vn),(SELECT o.hn FROM ipt o where o.an=h.vn )) as hn,(SELECT CONCAT(p.pname,p.fname,' ',p.lname) FROM patient p where p.hn=ifnull((SELECT o.hn FROM ovst o where o.vn=h.vn),(SELECT o.hn FROM ipt o where o.an=h.vn ))) as name,ifnull((SELECT o.vstdate FROM ovst o where o.vn=h.vn),(SELECT o.regdate FROM ipt o where o.an=h.vn)) as vstdate ,(SELECT o.dchdate FROM ipt o where o.an=h.vn ) as dchdate ,(SELECT IF(universal_item_value_text like '%0%','MIS',IF(universal_item_value_text like '%1%','ODS','')) FROM universal_detail WHERE universal_item_id = '731' AND universal_head_id = h.universal_head_id LIMIT 1) as type,ifnull((SELECT GROUP_CONCAT(o.icd10) FROM ovstdiag o where o.vn=h.vn),(SELECT  GROUP_CONCAT(o.icd10) FROM iptdiag o where o.an=h.vn )) as diag FROM universal_head h left outer join universal_detail d on d.universal_head_id = h.universal_head_id left outer join universal_item i on i.universal_item_id = d.universal_item_id WHERE h.universal_form_id = '70' AND vn = '$rs->vn'");


                $get_stoke = $connect_his->select(DB::raw("SELECT vn FROM opitemrece WHERE icode IN ('1530017','1490526') AND vn = '$rs->vn' AND an IS NULL"));

                $get_stemi = $connect_his->select(DB::raw("SELECT vn FROM opitemrece WHERE icode IN ('1530017','1490526') AND vn = '$rs->vn' AND an IS NULL"));

                if($get_query_income_2 != null OR $get_query_fp != null  OR $get_query_income_07_covid != NULL OR $get_query_morphine != NULL OR $get_query_tamox != NULL OR $get_query_chemo != NULL OR $get_query_er != NULL OR $get_query_er_oprefer != null OR $get_query_clopidogrel != NULL OR $get_query_dent_child != NULL OR $get_query_dent_child_2 != NULL OR $get_stemi != null OR $get_stoke != null OR $get_query_odsmis != null OR $get_opsi != null OR $get_opae != null OR $get_stoke != null OR $get_stemi != null){

                      $data_his[$key]['data'] = $rs;
                    if($get_query_income_2 != null){

                        $data_his[$key]['get_query_income_2'] = $get_query_income_2;
                      
                        $data_his[$key]['get_status_income'] = true;
                    }


                    if($get_query_fp != null){
                        $data_his[$key]['get_query_fp'] = $get_query_fp;
                        $data_his[$key]['get_status_fp'] = true;
                    }

                    if($get_query_refer != null){
                        $data_his[$key]['get_query_refer'] = $get_query_refer;
                        $data_his[$key]['get_status_refer'] = true;
                    }
                    
                    
                    if($get_query_morphine != NULL){
                        $data_his[$key]['get_query_morphine'] = $get_query_morphine;
                        $data_his[$key]['get_status_morphine'] = true;
                        
                    }if($get_query_tamox != NULL){
                        $data_his[$key]['get_query_tamox'] = $get_query_tamox;
                        $data_his[$key]['get_status_tamox'] = true;
                        
                    }
                    if($get_query_chemo != NULL){
                        $data_his[$key]['get_query_chemo'] = $get_query_chemo;
                        if($rs->pdx == 'C509' OR $rs->pdx == 'C187' OR $rs->pdx == 'C20'){
                            $data_his[$key]['text_chemo'] = 'YES';
                        }else{
                            $data_his[$key]['text_chemo'] = 'ERROR ICD10 CHEMO';
                        }
                        $data_his[$key]['get_status_chemo'] = true;
                    } 
                    

                    if($get_query_income_07_covid != null){
                        $data_his[$key]['get_query_income_07'] = $get_query_income_07;
                        $data_his[$key]['get_status_income_07'] = true;
                    }



                    if($get_query_er != NULL){
                        $data_his[$key]['get_query_er'] = $get_query_er;
                        $data_his[$key]['get_status_er'] = true;
                    }
                    if($get_query_er_oprefer != NULL){
                        $data_his[$key]['get_query_er_oprefer'] = $get_query_er_oprefer;
                        $data_his[$key]['get_status_er_oprefer'] = true;
                    }
                    if($get_query_clopidogrel != NULL){
                        $data_his[$key]['get_query_clopidogrel'] = $get_query_clopidogrel;
                        $data_his[$key]['get_status_clopidogrel'] = true;
                    }

                    if($get_query_dent_child != NULL){
                        $data_his[$key]['get_query_dent_child'] = $get_query_dent_child;
                        $data_his[$key]['get_status_dent_child'] = true;
                    }

                    if($get_query_dent_child_2 != NULL){
                        $data_his[$key]['get_query_dent_child_2'] = $get_query_dent_child_2;
                        $data_his[$key]['get_status_dent_child_2'] = true;
                    }

                    if($get_opsi != NULL){
                        $data_his[$key]['get_opsi'] = $get_opsi;
                        $data_his[$key]['get_status_opsi'] = true;
                    }

                    if($get_query_odsmis != NULL){
                        $data_his[$key]['get_query_odsmis'] = $get_query_odsmis;
                        $data_his[$key]['get_status_odsmis'] = true;
                    }

                    // if($get_claim != NULL){
                    //         $data_his[$key]->get_claim_send = $get_claim;
                    // }
                    // if($get_claim_12 != NULL){
                    //     $data_his[$key]->get_claim_import = $get_claim_12;
                    // }
                    if($get_stoke != NULL){
                        $data_his[$key]['get_stoke'] = $get_stoke;
                        $data_his[$key]['get_status_stoke'] = true;
                    }

                    if($get_stemi != NULL){
                        $data_his[$key]['get_stemi'] = $get_stemi;
                        $data_his[$key]['get_status_stemi'] = true;
                    }
                    if($get_opae != NULL){
                        $data_his[$key]['get_opae'] = $get_opae;
                        $data_his[$key]['get_status_opae'] = true;
                    }
                    // if($get_authen != NULL){
                    //     $data_his[$key]->get_authen = $get_authen;
                    // }

                    if($get_stemi != NULL){
                        $data_his[$key]['get_stemi'] = $get_stemi;
                        $data_his[$key]['get_status_stemi'] = true;
                    }
                    if($get_stoke != NULL){
                        $data_his[$key]['get_stoke'] = $get_stoke;
                        $data_his[$key]['get_status_stoke'] = true;
                    }
                    $key++;
                }

            }
        }
       return $data_his;
        // return view('claim::opd.get_data_view'); 

    }
    private function GetDataVn(){
        
        $connect_claim = DB::connection('mysql_239_claim');
       
        $sql = "SELECT * FROM tb_ipd_claim";

        $data_claim = $connect_claim->select($sql);
        $dataclaim_exits = [];
        foreach ($data_claim as $keyclaim => $rsclaim) {
           $dataclaim_exits[] = $rsclaim->an;
        }
        $exits_data=  implode(',',$dataclaim_exits);
        $connect_his = DB::connection('his_readonly');
        
        $datestart = '2022-07-01';
        $dateend = '2022-07-01';


        $sql = "SELECT ans.hn,p.cid,ans.an AS AN,ans.vn,ans.pttype,ans.dchdate,ans.pdx FROM an_stat AS ans LEFT OUTER JOIN pttype AS pt ON pt.pttype = ans.pttype  LEFT OUTER JOIN patient AS p ON p.hn = ans.hn WHERE  pt.pcode IN ('AE','AF','AB','AG','AA','AD','AC','AJ','AK','AH','UC') AND ans.dchdate BETWEEN '$datestart' AND '$dateend' AND ans.pdx <> ''  AND ans.an NOT IN ($exits_data) AND ans.pdx IS NOT NULL ORDER BY ans.dchdate ASC /*LIMIT 20*/";
        // $sql = "SELECT ans.hn,p.cid,ans.an AS AN,ans.vn,ans.pttype,ans.dchdate,ans.pdx FROM an_stat AS ans LEFT OUTER JOIN pttype AS pt ON pt.pttype = ans.pttype  LEFT OUTER JOIN patient AS p ON p.hn = ans.hn WHERE  pt.pcode IN ('AE','AF','AB','AG','AA','AD','AC','AJ','AK','AH','UC') AND ans.an = '650018937' AND ans.pdx <> ''  AND ans.pdx IS NOT NULL ORDER BY ans.dchdate ASC /*LIMIT 20*/";

        $data_his_set = $connect_his->select($sql);
        $data_his_set_filter =  ( json_decode(json_encode($data_his_set),true) );

        // $GetDataVn  = $data_his_set_filter;
        return $data_his_set_filter;
    }
    
    public function generate_text()
    {




        set_time_limit(0);

        // $GetDataVn = $this->GetDataVn();
        $get_data = $this->get_data();

        //  echo '<pre>';
        // print_r($get_data);
        // exit;
        // echo 'test';
        // exit;
       
        $GetDataVn = [];

        foreach ($get_data as $keyvn => $rsvn) {

            $GetDataVn[$keyvn]['vn'] = $rsvn['data']->vn;
        }
     


        $dateset = date("YmdHis");
        $generate_ins_sort_by = $this->generate_ins_sort_by($GetDataVn,$dateset);
        $generate_pat_sort_by = $this->generate_pat_sort_by($GetDataVn,$dateset);
        $generate_opd_sort_by = $this->generate_opd_sort_by($GetDataVn,$dateset);
        $generate_orf_sort_by = $this->generate_orf_sort_by($GetDataVn,$dateset);
        $generate_odx_sort_by = $this->generate_odx_sort_by($GetDataVn,$dateset);
        $generate_oop_sort_by = $this->generate_oop_sort_by($GetDataVn,$dateset);
        $generate_ipd_sort_by = $this->generate_ipd_sort_by($GetDataVn,$dateset);
        $generate_irf_sort_by = $this->generate_irf_sort_by($GetDataVn,$dateset);
        $generate_idx_sort_by = $this->generate_idx_sort_by($GetDataVn,$dateset);
        $generate_iop_sort_by = $this->generate_iop_sort_by($GetDataVn,$dateset);
        $generate_cht_sort_by = $this->generate_cht_sort_by($GetDataVn,$dateset);
        $generate_cha_sort_by = $this->generate_cha_sort_by($GetDataVn,$dateset);
        $generate_aer_sort_by = $this->generate_aer_sort_by($GetDataVn,$dateset);
        $generate_adp_sort_by = $this->generate_adp_sort_by($GetDataVn,$dateset);
        $generate_lvd_sort_by = $this->generate_lvd_sort_by($GetDataVn,$dateset);
        $generate_dru_sort_by = $this->generate_dru_sort_by($GetDataVn,$dateset);


        // return $generate_ins_sort_by;
    }

    private function generate_ins_sort_by($GetDataVn,$dateset){
        $connect_his = DB::connection('his_readonly');
        // $mytext = "HN|DATEOPD|CLINIC|REFER|REFERTYPE|SEQ|REFERDATE\n";
        $mytext = "HN|INSCL|SUBTYPE|CID|DATEIN|DATEEXP|HOSPMAIN|HOSPSUB|GOVCODE|GOVNAME|PERMITNO|DOCNO|OWNRPID|OWNNAME|AN|SEQ|SUBINSCL|RELINSCL|HTYPE\n";
        if($GetDataVn != null){

   

            foreach ($GetDataVn as $keys => $rs) {
                $vn =  $rs['vn'];
                $sql = "SELECT ovst.hn as HN,IFNULL(pt.hipdata_code,'') as INSCL,IFNULL(pt.hipdata_pttype,'')as SUBTYPE,
                IFNULL(p.cid,'') as CID,'10694' as HCODE,  REPLACE(vns.pttype_begin,'-','') AS DATEIN,
                IFNULL(DATE_FORMAT(v1.expire_date, '%Y%m%d'),'') as DATEEXP,
                IFNULL(v1.hospmain,'') as HOSPMAIN,IFNULL(v1.hospsub,'') as HOSPSUB,
                '' as GOVCODE,'' as GOVNAME,
                IF(trim(pt.hipdata_code)  = 'UCS' ,IFNULL(v1.pttypeno,''),
                IF(trim(pt.hipdata_code)  = 'OFC' ,IFNULL(v1.auth_code,''),'')) as PERMITNO,
                '' as DOCNO,'' as OWNRPID,'' as OWNNAME,

                IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,ovst.an,'') as AN, 
                IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,'',ovst.vn) as SEQ,
                '' as SUBINSCL,'' as RELINSCL,
                IF(trim(pt.hipdata_code)  = 'SSS' ,IF(v1.hospmain = '10694','1',IF(v1.hospsub = '10694','2','')),'') as HTYPE
                FROM ovst 
                LEFT OUTER JOIN patient p on p.hn = ovst.hn
                LEFT OUTER JOIN vn_stat vns on vns.vn = ovst.vn
                        LEFT OUTER JOIN visit_pttype v1 on v1.vn = ovst.vn # OPD เป็นอันนี้
                        #LEFT OUTER JOIN ipt_pttype v1 on v1.an = ovst.an  # IPD เป็นอันนี้
                        LEFT OUTER JOIN pttype pt on pt.pttype = ovst.pttype
                        WHERE ovst.vn = '$vn'";

                        $get_ins_by_vn = $connect_his->select($sql); 
                        if($get_ins_by_vn != null){


                        $data =  ( json_decode(json_encode($get_ins_by_vn),true) );

               
                        foreach ($data as $key => $value) {
                             // $value =  ( json_decode(json_encode($get_ins_by_vn[0]),true) );
                            $mytext .=  $value['HN'].'|'.$value['INSCL'].'|'.$value['SUBTYPE'].'|'.$value['CID'].'|'.$value['DATEIN'].'|'.$value['DATEEXP'].'|'.$value['HOSPMAIN'].'|'.$value['HOSPSUB'].'|'.$value['GOVCODE'].'|'.$value['GOVNAME'].'|'.$value['PERMITNO'].'|'.$value['DOCNO'].'|'.$value['OWNRPID'].'|'.$value['OWNNAME'].'|'.$value['AN'].'|'.$value['SEQ'].'|'.$value['SUBINSCL'].'|'.$value['RELINSCL'].'|'.$value['HTYPE']."\n";
                        }





                        
                     }               
                 }



             }
             Storage::disk('public_claim')->put($dateset.'/INS.txt',$mytext);
         }
         private function generate_pat_sort_by($GetDataVn,$dateset){
            $connect_his = DB::connection('his_readonly');

            $mytext = "HCODE|HN|CHANGWAT|AMPHUR|DOB|SEX|MARRIAGE|OCCUPA|NATION|PERSON_ID|NAMEPAT|TITLE|FNAME|LNAME|IDTYPE\n";
            if($GetDataVn != null){
                foreach ($GetDataVn as $keys => $rs) {
                    $vn =  $rs['vn'];
                    $sql = "SELECT '10694' as HCODE,ovst.hn as HN,
                    IFNULL(p.chwpart,'') as CHANGWAT,IFNULL(p.amppart,'') as AMPHUR,
                    IFNULL(DATE_FORMAT(p.birthday, '%Y%m%d'),'') as DOB,
                    IFNULL(p.sex,'') as SEX,IFNULL(m.nhso_marriage_code,'') as MARRIAGE,
                    IFNULL(o.nhso_code,'') as OCCUPA,IFNULL(n.nhso_code,'') as NATION,
                    IFNULL(p.cid,'') as PERSON_ID,CONCAT(IFNULL(p.fname,''),' ',IFNULL(p.lname,''),' , ',IFNULL(p.pname,''))  as NAMEPAT,
                    IFNULL(p.pname,'') as TITLE,IFNULL(p.fname,'') as FNAME,IFNULL(p.lname,'') as LNAME,'1' as IDTYPE

                    FROM ovst 
                    LEFT OUTER JOIN patient p on p.hn = ovst.hn
                    LEFT OUTER JOIN marrystatus m on m.`code` = p.marrystatus
                    LEFT OUTER JOIN occupation o on o.occupation = p.occupation
                    LEFT OUTER JOIN nationality n on n.nationality = p.nationality
                    WHERE ovst.vn = '$vn'";
                    $get_pat_by_vn = $connect_his->select($sql); 
                    if($get_pat_by_vn != null){

                        $data =  ( json_decode(json_encode($get_pat_by_vn),true) );
                        foreach ($data as $key => $value) {
                            // $value =  ( json_decode(json_encode($get_pat_by_vn[0]),true) );
                         $mytext .=  $value['HCODE'].'|'.
                         $value['HN'].'|'.
                         $value['CHANGWAT'].'|'.
                         $value['AMPHUR'].'|'.
                         $value['DOB'].'|'.
                         $value['SEX'].'|'.
                         $value['MARRIAGE'].'|'.
                         $value['OCCUPA'].'|'.
                         $value['NATION'].'|'.
                         $value['PERSON_ID'].'|'.
                         $value['NAMEPAT'].'|'.
                         $value['TITLE'].'|'.
                         $value['FNAME'].'|'.
                         $value['LNAME'].'|'.
                         $value['IDTYPE']."\n";
                        }
                     
                 }  

             }
         }


         Storage::disk('public_claim')->put($dateset.'/PAT.txt',$mytext);

     }
    private function generate_opd_sort_by($GetDataVn,$dateset){
        $connect_his = DB::connection('his_readonly');

        $mytext = "HN|CLINIC|DATEOPD|TIMEOPD|SEQ|UUC|DETAIL|BTEMP|SBP|DBP|PR|RR|OPTYPE|TYPEIN|TYPEOUT\n";
        if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
             $sql = "SELECT ovst.hn as HN,IFNULL(s1.nhso_code,'') as CLINIC,
             IFNULL(DATE_FORMAT(ovst.vstdate, '%Y%m%d'),'') as DATEOPD,IFNULL(DATE_FORMAT(ovst.vsttime, '%H%i'),'') as TIMEOPD,
             IFNULL(ovst.vn,'') as SEQ,
             IF((select COUNT(*) from opitemrece op where op.vn = ovst.vn AND op.pttype = ovst.pttype AND op.paidst in ('02') AND op.qty  > 0 AND op.unitprice  > 0) > 0,'1','2' ) as UUC,
             IF(TRIM(os.pe) <> '' ,os.pe,IFNULL(os.cc,'')) as DETAIL,
             IFNULL(ROUND(os.temperature,1) ,'') as BTEMP,
             IFNULL(ROUND(os.bps) ,'') as SBP,IFNULL(ROUND(os.bpd) ,'') as DBP,
             IFNULL(ROUND(os.pulse) ,'') as PR,IFNULL(ROUND(os.rr) ,'') as RR,
                #IF(ovst.spclty in ('15','21'),'9','') as OPTYPE,
             IF(IFNULL(oin.export_code,'') = '3',
             IF((SELECT hospcode.area_code from referin LEFT OUTER JOIN hospcode on hospcode.hospcode = referin.refer_hospcode WHERE referin.vn = ovst.vn LIMIT 1) = '3','0','1')
             ,IF(ovst.spclty in ('15','21'),'9','7')) as OPTYPE,
             IFNULL(oin.export_code,'') as TYPEIN,IFNULL(oout.export_code,'') as TYPEOUT
             FROM ovst 
             LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
             LEFT OUTER JOIN opdscreen os on os.vn = ovst.vn 
             LEFT OUTER JOIN ovstist oin on oin.ovstist = ovst.ovstist 
             LEFT OUTER JOIN ovstost oout on oout.ovstost = ovst.ovstost 
             WHERE ovst.vn = '$vn'
             AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
             $get_opd_by_vn = $connect_his->select($sql); 
             if($get_opd_by_vn != null){
                $data =  ( json_decode(json_encode($get_opd_by_vn),true) );
                foreach ($data as $key => $value) {
                     // $value =  ( json_decode(json_encode($get_opd_by_vn[0]),true) );
                     $mytext .= $value['HN'].'|'.
                     $value['CLINIC'].'|'.
                     $value['DATEOPD'].'|'.
                     $value['TIMEOPD'].'|'.
                     $value['SEQ'].'|'.
                     $value['UUC']."||||||||||\n";
                }
                 
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/OPD.txt',$mytext);
    }
    private function generate_orf_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|DATEOPD|CLINIC|REFER|REFERTYPE|SEQ|REFERDATE\n" ;
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "(SELECT ovst.hn as HN,IFNULL(DATE_FORMAT(ovst.vstdate, '%Y%m%d'),'') as DATEOPD,IFNULL(s1.nhso_code,'') as CLINIC,
                    IFNULL(r.refer_hospcode,'')  as REFER,'1' as REFERTYPE,
                    IFNULL(ovst.vn,'')as SEQ,IFNULL(DATE_FORMAT(r.refer_date, '%Y%m%d'),'') as REFERDATE
                    FROM ovst 
                    JOIN referin r on r.vn = ovst.vn
                    LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
                    WHERE ovst.vn = '$vn' 
                    AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an))
                    UNION ALL
                    (SELECT ovst.hn as HN,IFNULL(DATE_FORMAT(ovst.vstdate, '%Y%m%d'),'') as DATEOPD,IFNULL(s1.nhso_code,'') as CLINIC,
                    IFNULL(r.refer_hospcode,'')  as REFER,'2' as REFERTYPE,
                    IFNULL(ovst.vn,'')as SEQ,IFNULL(DATE_FORMAT(r.refer_date, '%Y%m%d'),'') as REFERDATE
                    FROM ovst 
                    JOIN referout r on r.vn = ovst.vn
                    LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
                    WHERE ovst.vn = '$vn' 
                    AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an))";
             $get_orf_by_vn = $connect_his->select($sql); 
             if($get_orf_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_orf_by_vn),true) );
                foreach ($data as $key => $value) {
                    // $value =  ( json_decode(json_encode($get_orf_by_vn[0]),true) );
                    $mytext .=  $value['HN'].'|'.
                    $value['DATEOPD'].'|'.
                    $value['CLINIC'].'|'.
                    $value['REFER'].'|'.
                    $value['REFERTYPE'].'||'.
                    $value['SEQ']."\n";
                }
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/ORF.txt',$mytext);

    }
    private function generate_odx_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|DATEDX|CLINIC|DIAG|DXTYPE|DRDX|PERSON_ID|SEQ\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT ovst.hn as HN,
                    IFNULL(DATE_FORMAT(diag1.vstdate, '%Y%m%d'),'') as DATEDX,
                    IFNULL(s1.nhso_code,'') as CLINIC,
                    IFNULL(diag1.icd10,'') as DIAG,IFNULL(dxtype.nhso_code,'') as DXTYPE,IFNULL(doctor.licenseno,'') as DRDX,
                    IFNULL(p.cid,'') as PERSON_ID,IFNULL(ovst.vn,'')as SEQ
                    FROM ovst 
                    JOIN ovstdiag diag1 on diag1.vn = ovst.vn
                    LEFT OUTER JOIN patient p on p.hn = ovst.hn
                    LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
                    LEFT OUTER JOIN doctor on doctor.`code` = diag1.doctor
                    LEFT OUTER JOIN diagtype dxtype on dxtype.diagtype = diag1.diagtype
                    WHERE ovst.vn = '$vn' AND SUBSTRING(diag1.icd10, 1, 1) NOT REGEXP '^[0-9]+$'
                    AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
             $get_odx_by_vn = $connect_his->select($sql); 
             if($get_odx_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_odx_by_vn),true) );
                foreach ($data as $key => $value) {
                // $value =  ( json_decode(json_encode($get_odx_by_vn[0]),true) );   
                        $mytext .=  $value['HN'].'|'.
                        $value['DATEDX'].'|'.
                        $value['CLINIC'].'|'.
                        $value['DIAG'].'|'.
                        $value['DXTYPE'].'|'.
                        $value['DRDX'].'|'.
                        $value['PERSON_ID'].'|'.
                        $value['SEQ']."\n";
                }
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/ODX.txt',$mytext);

    }
    private function generate_oop_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|DATEOPD|CLINIC|OPER|DROPID|PERSON_ID|SEQ|SERVPRICE\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT ovst.hn as HN,
                IFNULL(DATE_FORMAT(ovst.vstdate, '%Y%m%d'),'') as DATEOPD,
                IFNULL(s1.nhso_code,'') as CLINIC,
                IFNULL(diag1.icd10,'') as OPER,IFNULL(doctor.licenseno,'') as DROPID,
                IFNULL(p.cid,'') as PERSON_ID,IFNULL(ovst.vn,'')as SEQ,
                IFNULL(ROUND((select if(IFNULL(SUM(op.sum_price),0) <> IFNULL( SUM(op.qty*op.unitprice),0) , IFNULL( SUM(op.qty*op.unitprice),0),IFNULL( SUM(op.sum_price),0))
                 from opitemrece  op 
                 LEFT OUTER JOIN er_oper_code e1  on e1.icode = op.icode AND TRIM(e1.icd9cm) <> ''
                 LEFT OUTER JOIN ipt_oper_code i1  on i1.icode = op.icode AND TRIM(i1.icd9cm) <> ''
                 LEFT OUTER JOIN dttm dttm  on dttm.icode = op.icode AND TRIM(dttm.icd9cm) <> ''
                 LEFT OUTER JOIN physic_items p1  on p1.icode = op.icode     AND TRIM(p1.icd9) <> ''
                 where op.vn = ovst.vn  AND op.paidst in ('02') AND op.qty  > 0 AND op.unitprice  > 0
                 AND ( TRIM(e1.icd9cm) = diag1.icd10 or TRIM(i1.icd9cm) = diag1.icd10 or TRIM(dttm.icd9cm) = diag1.icd10 or TRIM(e1.icd9cm) = diag1.icd10 )
                 GROUP BY ovst.vn  limit 1 ),2),'0.00') as SERVPRICE
                FROM ovst 
                JOIN ovstdiag diag1 on diag1.vn = ovst.vn
                LEFT OUTER JOIN patient p on p.hn = ovst.hn
                LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
                LEFT OUTER JOIN doctor on doctor.`code` = diag1.doctor
                WHERE ovst.vn = '$vn' AND SUBSTRING(diag1.icd10, 1, 1) REGEXP '^[0-9]+$'
                AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
             $get_oop_by_vn = $connect_his->select($sql); 
             if($get_oop_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_oop_by_vn),true) );
                foreach ($data as $key => $value) {
                    // $value =  ( json_decode(json_encode($get_oop_by_vn[0]),true) );
                    $mytext .=  $value['HN'].'|'.
                    $value['DATEOPD'].'|'.
                    $value['CLINIC'].'|'.
                    $value['OPER'].'|'.
                    $value['DROPID'].'|'.
                    $value['PERSON_ID'].'|'.
                    $value['SEQ']."|\n";
                } // endforeach data
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/OOP.txt',$mytext);

    }
    private function generate_ipd_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|AN|DATEADM|TIMEADM|DATEDSC|TIMEDSC|DISCHS|DISCHT|WARDDSC|DEPT|ADM_W|UUC|SVCTYPE\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,
                    IFNULL(DATE_FORMAT(ipt.regdate, '%Y%m%d'),'') as DATEADM,IFNULL(DATE_FORMAT(ipt.regtime, '%H%i'),'') as TIMEADM,
                    IFNULL(DATE_FORMAT(ipt.dchdate, '%Y%m%d'),'') as DATEDSC,IFNULL(DATE_FORMAT(ipt.dchtime, '%H%i'),'') as TIMEDSC,
                    IFNULL(dchstts.nhso_dchstts,'') as DISCHS,IFNULL(dchtype.nhso_dchtype,'') as DISCHT,
                    IFNULL(s1.nhso_code,'') as WARDDSC,IFNULL(s2.nhso_code,'') as DEPT,
                    IFNULL(ROUND(ipt.bw*0.001,2),'')  as ADM_W,
                    IF((select COUNT(*) from opitemrece  op where op.an = ovst.an AND op.paidst in ('02') AND op.qty  > 0 AND op.unitprice  > 0) > 0,'1','2' ) as UUC,
                    'I' as SVCTYPE
                    FROM ovst 
                    JOIN ipt on ipt.an = ovst.an
                    LEFT OUTER JOIN vn_stat on vn_stat.vn = ovst.vn
                    LEFT OUTER JOIN dchstts on dchstts.dchstts = ipt.dchstts
                    LEFT OUTER JOIN dchtype on dchtype.dchtype = ipt.dchtype
                    LEFT OUTER JOIN spclty s1 on s1.spclty = ipt.spclty
                    LEFT OUTER JOIN spclty s2 on s2.spclty = vn_stat.spclty
                    WHERE ovst.vn = '$vn'";
             $get_ipd_by_vn = $connect_his->select($sql); 
             if($get_ipd_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_ipd_by_vn),true) );
                foreach ($data as $key => $value) {
                    // $value =  ( json_decode(json_encode($get_ipd_by_vn[0]),true) );
                    $mytext .=  $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['DATEADM'].'|'.
                    $value['TIMEADM'].'|'.
                    $value['DATEDSC'].'|'.
                    $value['TIMEDSC'].'|'.
                    $value['DISCHS'].'|'.
                    $value['DISCHT'].'|'.
                    $value['WARDDSC'].'|'.
                    $value['DEPT'].'|'.
                    $value['ADM_W'].'|'.
                    $value['UUC'].'|'.
                    $value['SVCTYPE']."\n";
                }
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/IPD.txt',$mytext);

    }
    private function generate_irf_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "AN|REFER|REFERTYPE\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "(SELECT IFNULL(ovst.an,'') as AN,
                IFNULL(r.refer_hospcode,'')  as REFER,'1' as REFERTYPE
                FROM ovst
                JOIN referin r on r.vn = ovst.vn
                WHERE ovst.vn = '$vn' AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an))
                UNION ALL
                (SELECT IFNULL(ovst.an,'') as AN, 
                IFNULL(r.refer_hospcode,'')  as REFER,'2' as REFERTYPE
                FROM ovst
                JOIN referout r on r.vn = ovst.an
                WHERE ovst.vn = '$vn'  AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an))";
             $get_ipd_by_vn = $connect_his->select($sql); 
             if($get_ipd_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_ipd_by_vn),true) );
                foreach ($data as $key => $value) {
                    // $value =  ( json_decode(json_encode($get_ipd_by_vn[0]),true) );

                    $mytext .=  $value['AN'].'|'.
                    $value['REFER'].'|'.
                    $value['REFER'].'|'.
                    $value['REFERTYPE']."\n";
                }
                
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/IRF.txt',$mytext);

    }
    private function generate_idx_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "AN|DIAG|DXTYPE|DRDX\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT IFNULL(ovst.an,'') as AN, 
                    IFNULL(diag1.icd10,'') as DIAG,IFNULL(dxtype.nhso_code,'') as DXTYPE,
                    IFNULL(doctor.licenseno,'') as DRDX
                    FROM ovst
                    JOIN iptdiag diag1  on diag1.an = ovst.an
                    LEFT OUTER JOIN diagtype dxtype on dxtype.diagtype = diag1.diagtype
                    LEFT OUTER JOIN doctor on doctor.`code` = diag1.doctor
                    WHERE ovst.vn = '$vn'
                    AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
                    ORDER BY dxtype.nhso_code";
             $get_idx_by_vn = $connect_his->select($sql); 
             if($get_idx_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_idx_by_vn),true) );
                foreach ($data as $key => $value) {
                    // $value =  ( json_decode(json_encode($get_idx_by_vn[0]),true) );
                    $mytext .=  $value['AN'].'|'.
                    $value['DIAG'].'|'.
                    $value['DXTYPE'].'|'.
                    $value['DRDX']."\n";

                }
                
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/IDX.txt',$mytext);

    }
    private function generate_iop_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "AN|OPER|OPTYPE|DROPID|DATEIN|TIMEIN|DATEOUT|TIMEOUT\n";

          if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT IFNULL(ovst.an,'') as AN,IF(o.ext_code<>'',concat(o.icd9,'+',o.ext_code),o.icd9) as OPER,o.oper_type as OPTYPE,d.licenseno as DROPID,DATE_FORMAT(o.opdate, '%Y%m%d') as DATEIN,concat(SUBSTRING(o.optime,1,2),SUBSTRING(o.optime,4,2)) as TIMEIN,DATE_FORMAT(o.enddate, '%Y%m%d') as DATEOUT,concat(SUBSTRING(o.endtime,1,2),SUBSTRING(o.endtime,4,2)) as TIMEOUT
                from ovst 
                LEFT OUTER JOIN an_stat a on a.an = ovst.an 
                LEFT OUTER JOIN patient p on p.hn = ovst.hn
                LEFT OUTER JOIN iptoprt o on o.an = ovst.an
                LEFT OUTER JOIN doctor d on d.code = o.doctor
                WHERE ovst.vn = '$vn'
                AND  EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
             $get_cht_by_vn = $connect_his->select($sql); 
             if($get_cht_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_cht_by_vn),true) );
                foreach ($data as $key => $value) {
                     // $value =  ( json_decode(json_encode($get_cht_by_vn[0]),true) );

                    $mytext .=   $value['AN'].'|'.
                    $value['OPER'].'|'.
                    $value['OPTYPE'].'|'.
                    $value['DROPID'].'|'.
                    $value['DATEIN'].'|'.
                    $value['TIMEIN'].'|'.
                    $value['DATEOUT'].'|'.
                    $value['TIMEOUT']."\n";
                }
               
             }
            }
        }


        Storage::disk('public_claim')->put($dateset.'/IOP.txt',$mytext);

    }


    //    private function generate_cht_sort_by($GetDataVn,$dateset){
    //      $connect_his = DB::connection('his_readonly');

    //      $mytext = "HN|AN|DATE|TOTAL|PAID|PTTYPE|PERSON_ID|SEQ\n";;
    //      if($GetDataVn != null){
    //         foreach ($GetDataVn as $keys => $rs) {
    //          $vn =  $rs['vn'];
            
    //          $sql = "SELECT ovst.hn as HN,'' as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE, REPLACE(FORMAT(v.income,2),',','') as TOTAL, REPLACE(FORMAT(v.paid_money,2),',','') as PAID
    //         ,if(ovst.pttype='10',10,v.pcode) as PTTYPE,v.cid as PERSON_ID,ovst.vn as SEQ,'' as OPD_MEMO,'' as INVOICE_NO,'' as INVOICE_LT
    //         from ovst
    //         LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
    //         WHERE ovst.vn IN ('$vn')
    //         AND NOT EXISTS (SELECT * FROM ipt WHERE ovst.an = ipt.an)";
    //          $get_cht_by_vn = $connect_his->select($sql); 
    //          if($get_cht_by_vn != null){
    //             $data =  ( json_decode(json_encode($get_cht_by_vn),true) );               
    //             foreach ($data as $key => $value) {
                    
    //                $mytext .=   $value['HN'].'|'.
    //                 $value['AN'].'|'.
    //                 $value['DATE'].'|'.
    //                 $value['TOTAL'].'|'.
    //                 $value['PAID'].'|'.
    //                 $value['PTTYPE'].'|'.
    //                 $value['PERSON_ID'].'|'.
    //                 $value['SEQ']."\n";

    //             }
              



    //          }
    //         }
    //     }
    //     Storage::disk('public_claim')->put($dateset.'/CHT.txt',$mytext);

    // }
    private function generate_cht_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|AN|DATE|TOTAL|PAID|PTTYPE|PERSON_ID|SEQ\n";;
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];
            
             $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE, ROUND(v.income,2) as TOTAL, ROUND(v.paid_money,2) as PAID
                ,if(ovst.pttype='10',10,v.pcode) as PTTYPE,v.cid as PERSON_ID,ovst.vn as SEQ,'' as OPD_MEMO,'' as INVOICE_NO,'' as INVOICE_LT
                from ovst 
                LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
                WHERE ovst.vn = '$vn'
                AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)

                UNION

                SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( a.dchdate, '%Y%m%d') as DATE,  ROUND(a.income,2) as TOTAL, ROUND(a.paid_money,2) as PAID
                ,if(ovst.pttype='10',10,a.pcode) as PTTYPE,p.cid as PERSON_ID,IFNULL(ovst.an,'') as SEQ,'' as OPD_MEMO,'' as INVOICE_NO,'' as INVOICE_LT
                from ovst 
                LEFT OUTER JOIN an_stat a on a.an = ovst.an
                LEFT OUTER JOIN patient p on p.hn = ovst.hn
                WHERE ovst.vn = '$vn'
                AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
             $get_cht_by_vn = $connect_his->select($sql); 
             if($get_cht_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_cht_by_vn),true) );

               
                foreach ($data as $key => $value) {
                    
                    $mytext .=   $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['DATE'].'|'.
                    $value['TOTAL'].'|'.
                    $value['PAID'].'|'.
                    $value['PTTYPE'].'|'.
                    $value['PERSON_ID'].'|'.
                    $value['SEQ']."\n";
                }


                // $value =  ( json_decode(json_encode($get_cht_by_vn[0]),true) );
                // $mytext .=   $value['HN'].'|'.
                // $value['AN'].'|'.
                // $value['DATE'].'|'.
                // $value['TOTAL'].'|'.
                // $value['PAID'].'|'.
                // $value['PTTYPE'].'|'.
                // $value['PERSON_ID'].'|'.
                // $value['SEQ']."\n";
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/CHT.txt',$mytext);

    }

    // private function generate_cht_sort_by($GetDataVn,$dateset){
    //      $connect_his = DB::connection('his_readonly');

    //      $mytext = "HN|AN|DATE|TOTAL|PAID|PTTYPE|PERSON_ID|SEQ\n";;
    //      if($GetDataVn != null){
    //         foreach ($GetDataVn as $keys => $rs) {
    //          $vn =  $rs['vn'];
            
    //          $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE, REPLACE(FORMAT(v.income,2),',','') as TOTAL, REPLACE(FORMAT(v.paid_money,2),',','') as PAID
    //             ,if(ovst.pttype='10',10,v.pcode) as PTTYPE,v.cid as PERSON_ID,ovst.vn as SEQ,'' as OPD_MEMO,'' as INVOICE_NO,'' as INVOICE_LT
    //             from ovst 
    //             LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
    //             WHERE ovst.vn = '$vn'
    //             AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)

    //             UNION

    //             SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( a.dchdate, '%Y%m%d') as DATE, REPLACE(FORMAT(a.income,2),',','') as TOTAL, REPLACE(FORMAT(a.paid_money,2),',','') as PAID
    //             ,if(ovst.pttype='10',10,a.pcode) as PTTYPE,p.cid as PERSON_ID,IFNULL(ovst.an,'') as SEQ,'' as OPD_MEMO,'' as INVOICE_NO,'' as INVOICE_LT
    //             from ovst 
    //             LEFT OUTER JOIN an_stat a on a.an = ovst.an
    //             LEFT OUTER JOIN patient p on p.hn = ovst.hn
    //             WHERE ovst.vn = '$vn'
    //             AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
    //          $get_cht_by_vn = $connect_his->select($sql); 
    //          if($get_cht_by_vn != null){
                
    //             $data =  ( json_decode(json_encode($get_cht_by_vn),true) );

               
    //             foreach ($data as $key => $value) {
                    
    //                 $mytext .=   $value['HN'].'|'.
    //                 $value['AN'].'|'.
    //                 $value['DATE'].'|'.
    //                 $value['TOTAL'].'|'.
    //                 $value['PAID'].'|'.
    //                 $value['PTTYPE'].'|'.
    //                 $value['PERSON_ID'].'|'.
    //                 $value['SEQ']."\n";
    //             }


    //             // $value =  ( json_decode(json_encode($get_cht_by_vn[0]),true) );
    //             // $mytext .=   $value['HN'].'|'.
    //             // $value['AN'].'|'.
    //             // $value['DATE'].'|'.
    //             // $value['TOTAL'].'|'.
    //             // $value['PAID'].'|'.
    //             // $value['PTTYPE'].'|'.
    //             // $value['PERSON_ID'].'|'.
    //             // $value['SEQ']."\n";
    //          }
    //         }
    //     }
    //     Storage::disk('public_claim')->put($dateset.'/CHT.txt',$mytext);

    // }

    private function generate_cha_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HN|AN|DATE|CHRGITEM|AMOUNT|PERSON_ID|SEQ\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];    

             $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,IF(pt.pcode = 'A9','K1',IF(o.paidst = '03',dc.chrgitem_code2,dc.chrgitem_code1)) as CHRGITEM,ROUND(sum(o.sum_price), 2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ
from ovst 
LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn 
LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
LEFT OUTER JOIN pttype pt on pt.pttype = o.pttype
LEFT OUTER JOIN income i on i.income = o.income
LEFT OUTER JOIN drg_chrgitem dc on dc.drg_chrgitem_id = i.drg_chrgitem_id
WHERE ovst.vn = '$vn'
AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
GROUP BY o.vn,o.income,o.paidst

UNION 

SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( a.dchdate, '%Y%m%d') as DATE,IF(pt.pcode = 'A9','K1',IF(o.paidst = '03',dc.chrgitem_code2,dc.chrgitem_code1)) as CHRGITEM,ROUND(sum(o.sum_price), 2) as AMOUNT,p.cid as PERSON_ID,IFNULL(ovst.an,'') as SEQ
from ovst 
LEFT OUTER JOIN an_stat a on a.an = ovst.an 
LEFT OUTER JOIN patient p on p.hn = ovst.hn
LEFT OUTER JOIN opitemrece o on o.an = ovst.an
LEFT OUTER JOIN pttype pt on pt.pttype = o.pttype
LEFT OUTER JOIN income i on i.income = o.income
LEFT OUTER JOIN drg_chrgitem dc on dc.drg_chrgitem_id = i.drg_chrgitem_id
WHERE ovst.vn = '$vn'
AND  EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
GROUP BY o.an,o.income,o.paidst
";


            //  $sql = "SELECT o.icode,ovst.hn as HN,'' as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,dc.chrgitem_code1 as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ ,
            // dc.chrgitem_code1,dc.chrgitem_code2
            // FROM ovst 
            //     LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
            //     LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
            //   LEFT OUTER JOIN income AS inc ON inc.income = o.income
            //     LEFT OUTER JOIN drg_chrgitem AS dc ON inc.drg_chrgitem_id = dc.drg_chrgitem_id
            //     WHERE ovst.vn IN ('$vn')
            //     AND NOT EXISTS (SELECT * FROM ipt WHERE ovst.an = ipt.an) AND o.paidst NOT IN ('01','03')
            // GROUP BY dc.chrgitem_code1 , ovst.vn
            
            
            // UNION
            
            // SELECT o.icode,ovst.hn as HN,'' as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,dc.chrgitem_code2 as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ ,
            // dc.chrgitem_code1,dc.chrgitem_code2
            // FROM ovst 
            //     LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
            //     LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
            //   LEFT OUTER JOIN income AS inc ON inc.income = o.income
            //     LEFT OUTER JOIN drg_chrgitem AS dc ON inc.drg_chrgitem_id = dc.drg_chrgitem_id
            //     WHERE ovst.vn IN ('$vn')
            //     AND NOT EXISTS (SELECT * FROM ipt WHERE ovst.an = ipt.an) AND o.paidst  IN ('01','03')
            // GROUP BY dc.chrgitem_code2 , ovst.vn";
             $get_cha_by_vn = $connect_his->select($sql); 
            if($get_cha_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_cha_by_vn),true) );

               
                foreach ($data as $key => $value) {
                    
                    $mytext .=   $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['DATE'].'|'.
                    $value['CHRGITEM'].'|'.
                    $value['AMOUNT'].'|'.
                    $value['PERSON_ID'].'|'.
                    $value['SEQ']."\n";
                }
              


                
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/CHA.txt',$mytext);

    }
    // private function generate_cha_sort_by($GetDataVn,$dateset){
    //      $connect_his = DB::connection('his_readonly');

    //      $mytext = "HN|AN|DATE|CHRGITEM|AMOUNT|PERSON_ID|SEQ\n";
    //      if($GetDataVn != null){
    //         foreach ($GetDataVn as $keys => $rs) {
    //          $vn =  $rs['vn'];    

    //          $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,o.income as CHRGITEM,REPLACE(FORMAT(sum(o.sum_price),2),',','') as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ
    //                 from ovst 
    //                 LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn 
    //                 LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
    //                 WHERE ovst.vn = '$vn'
    //                 AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
    //                 GROUP BY o.vn,o.income

    //                 UNION 

    //                 SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( a.dchdate, '%Y%m%d') as DATE,o.income as CHRGITEM,REPLACE(FORMAT(sum(o.sum_price),2),',','') as AMOUNT,p.cid as PERSON_ID,IFNULL(ovst.an,'') as SEQ
    //                 from ovst 
    //                 LEFT OUTER JOIN an_stat a on a.an = ovst.an 
    //                 LEFT OUTER JOIN patient p on p.hn = ovst.hn
    //                 LEFT OUTER JOIN opitemrece o on o.an = ovst.an
    //                 WHERE ovst.vn = '$vn'
    //                 AND  EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
    //                 GROUP BY o.an,o.income";


    //         //  $sql = "SELECT o.icode,ovst.hn as HN,'' as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,dc.chrgitem_code1 as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ ,
    //         // dc.chrgitem_code1,dc.chrgitem_code2
    //         // FROM ovst 
    //         //     LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
    //         //     LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
    //         //   LEFT OUTER JOIN income AS inc ON inc.income = o.income
    //         //     LEFT OUTER JOIN drg_chrgitem AS dc ON inc.drg_chrgitem_id = dc.drg_chrgitem_id
    //         //     WHERE ovst.vn IN ('$vn')
    //         //     AND NOT EXISTS (SELECT * FROM ipt WHERE ovst.an = ipt.an) AND o.paidst NOT IN ('01','03')
    //         // GROUP BY dc.chrgitem_code1 , ovst.vn
            
            
    //         // UNION
            
    //         // SELECT o.icode,ovst.hn as HN,'' as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,dc.chrgitem_code2 as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ ,
    //         // dc.chrgitem_code1,dc.chrgitem_code2
    //         // FROM ovst 
    //         //     LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
    //         //     LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
    //         //   LEFT OUTER JOIN income AS inc ON inc.income = o.income
    //         //     LEFT OUTER JOIN drg_chrgitem AS dc ON inc.drg_chrgitem_id = dc.drg_chrgitem_id
    //         //     WHERE ovst.vn IN ('$vn')
    //         //     AND NOT EXISTS (SELECT * FROM ipt WHERE ovst.an = ipt.an) AND o.paidst  IN ('01','03')
    //         // GROUP BY dc.chrgitem_code2 , ovst.vn";
    //          $get_cha_by_vn = $connect_his->select($sql); 
    //         if($get_cha_by_vn != null){
                
    //             $data =  ( json_decode(json_encode($get_cha_by_vn),true) );

               
    //             foreach ($data as $key => $value) {
                    
    //                 $mytext .=   $value['HN'].'|'.
    //                 $value['AN'].'|'.
    //                 $value['DATE'].'|'.
    //                 $value['CHRGITEM'].'|'.
    //                 $value['AMOUNT'].'|'.
    //                 $value['PERSON_ID'].'|'.
    //                 $value['SEQ']."\n";
    //             }
              


                
    //          }
    //         }
    //     }
    //     Storage::disk('public_claim')->put($dateset.'/CHA.txt',$mytext);

    // }



    // private function generate_cha_sort_by($GetDataVn,$dateset){
    //      $connect_his = DB::connection('his_readonly');

    //      $mytext = "HN|AN|DATE|CHRGITEM|AMOUNT|PERSON_ID|SEQ\n";
    //      if($GetDataVn != null){
    //         foreach ($GetDataVn as $keys => $rs) {
    //          $vn =  $rs['vn'];       
    //          $sql = "SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE,o.income as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,v.cid as PERSON_ID,ovst.vn as SEQ
    //                 from ovst 
    //                 LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn 
    //                 LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
    //                 WHERE ovst.vn = '$vn'
    //                 AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
    //                 GROUP BY o.vn,o.income

    //                 UNION 

    //                 SELECT ovst.hn as HN,IFNULL(ovst.an,'') as AN,DATE_FORMAT( a.dchdate, '%Y%m%d') as DATE,o.income as CHRGITEM,FORMAT(sum(o.sum_price),2) as AMOUNT,p.cid as PERSON_ID,IFNULL(ovst.an,'') as SEQ
    //                 from ovst 
    //                 LEFT OUTER JOIN an_stat a on a.an = ovst.an 
    //                 LEFT OUTER JOIN patient p on p.hn = ovst.hn
    //                 LEFT OUTER JOIN opitemrece o on o.an = ovst.an
    //                 WHERE ovst.vn = '$vn'
    //                 AND  EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)
    //                 GROUP BY o.an,o.income";
    //          $get_cha_by_vn = $connect_his->select($sql); 
    //          if($get_cha_by_vn != null){
                
    //             $data =  ( json_decode(json_encode($get_cha_by_vn),true) );

               
    //             foreach ($data as $key => $value) {
                    
    //                 $mytext .=   $value['HN'].'|'.
    //                 $value['AN'].'|'.
    //                 $value['DATE'].'|'.
    //                 $value['CHRGITEM'].'|'.
    //                 $value['AMOUNT'].'|'.
    //                 $value['PERSON_ID'].'|'.
    //                 $value['SEQ']."\n";
    //             }
              


                
    //          }
    //         }
    //     }
    //     Storage::disk('public_claim')->put($dateset.'/CHA.txt',$mytext);

    // }
    private function generate_aer_sort_by($GetDataVn,$dateset){     
         $connect_his = DB::connection('his_readonly');

        $mytext = "HN|AN|DATEOPD|AUTHAE|AEDATE|AETIME|AETYPE|REFER_NO|REFMAINI|IREFTYPE|REFMAINO|OREFTYPE|UCAE|EMTYPE|SEQ|AESTATUS|DALERT|TALERT\n";   

          if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
                   $vn =  $rs['vn'];       
                 $sql = "SELECT o.hn as HN,IFNULL(o.an,'') as AN,DATE_FORMAT( o.vstdate, '%Y%m%d') as DATEOPD,'' as AUTHAE,DATE_FORMAT(er_regist.enter_er_time, '%Y%m%d') as AEDATE
                ,concat(SUBSTRING(er_regist.enter_er_time,12,2),SUBSTRING(er_regist.enter_er_time,15,2)) as AETIME,'' as AETYPE,'' as REFER_NO,o.rfrilct as REFMAINI
                ,'' as IREFTYPE,o.rfrolct as REFMAINO,'' as OREFTYPE,IF(er_regist.er_pt_type='2','A',IF(er_regist.er_pt_type='1','E','')) as UCAE,'' as EMTYPE,IFNULL(o.an,o.vn) as SEQ,'' as AESTATUS,'' as DALERT,'' as TALERT
                FROM er_regist 
                LEFT OUTER JOIN ovst o on o.vn = er_regist.vn
                where o.vn = '$vn'";
             $get_dru_by_vn = $connect_his->select($sql); 
             if($get_dru_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_dru_by_vn),true) );

               
                foreach ($data as $key => $value) {
                    
                

                    $mytext .=   $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['DATEOPD'].'|'.
                    $value['AUTHAE'].'|'.
                    $value['AEDATE'].'|'.
                    $value['AETIME'].'|'.
                    $value['AETYPE'].'|'.
                    $value['REFER_NO'].'|'.
                    $value['REFMAINI'].'|'.
                    $value['IREFTYPE'].'|'.
                    $value['REFMAINO'].'|'.
                    $value['OREFTYPE'].'|'.
                    $value['UCAE'].'|'.
                    $value['EMTYPE'].'|'.
                    $value['SEQ'].'|'.
                    $value['AESTATUS'].'|'.
                    $value['DALERT'].'|'.
                    $value['TALERT']."\n";
                }
              
            }
        }
    }
       


        Storage::disk('public_claim')->put($dateset.'/AER.txt',$mytext);
    }
    private function generate_adp_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         // $mytext = "HN|AN|DATEOPD|TYPE|CODE|QTY|RATE|SEQ|CAGCODE|DOSE|CA_TYPE|SERIALNO|TOTCOPAY|USE_STATUS|TOTAL|QTYDAY|TMLTCODE\n";
         $mytext = "HN|AN|DATEOPD|TYPE|CODE|QTY|RATE|SEQ|CAGCODE|DOSE|CA_TYPE|SERIALNO|TOTCOPAY|USE_STATUS|TOTAL|QTYDAY|TMLTCODE|STATUS1|BI|CLINIC|ITEMSRC|PROVIDER|GRAVIDA|GA_WEEK|DCIP|LMP\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];       
             $sql = "SELECT ovst.hn as HN,IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,ovst.an,'') as AN, 
IFNULL(DATE_FORMAT(ovst.vstdate, '%Y%m%d'),'') as DATEOPD,
IFNULL(if(SUBSTRING(op.icode, 1, 1) = '1',(SELECT d.nhso_adp_type_id FROM drugitems d  WHERE  d.icode = op.icode limit 1),
        (SELECT nd.nhso_adp_type_id FROM nondrugitems nd  WHERE  nd.icode = op.icode limit 1))
              ,'') as TYPE,
IFNULL(if(SUBSTRING(op.icode, 1, 1) = '1',(SELECT d.nhso_adp_code FROM drugitems d  WHERE  d.icode = op.icode limit 1),
        (SELECT nd.nhso_adp_code FROM nondrugitems nd  WHERE  nd.icode = op.icode limit 1))
              ,'') as CODE,
ROUND(SUM(IFNULL(op.qty,0)),0) as QTY,IFNULL(ROUND(op.unitprice,2) ,'0.00') as RATE,
IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,'',ovst.vn) as SEQ,
'' as CAGCODE,
IF(pt.hipdata_code in ('SSS') AND SUBSTRING(op.icode, 1, 1) = '1',
        IFNULL((SELECT d.strength FROM drugitems d  WHERE  d.icode = op.icode limit 1),''), '') as DOSE,
'' as CA_TYPE,'' as SERIALNO,

ROUND(IFNULL(SUM(CASE WHEN op.paidst not in ('02') THEN IF(op.sum_price <> op.qty*op.unitprice ,op.qty*op.unitprice,op.sum_price) ELSE 0 END),0.00),2)  as TOTCOPAY,

IF(pt.hipdata_code in ('OFC','LGO') AND IFNULL(if(SUBSTRING(op.icode, 1, 1) = '1',(SELECT d.nhso_adp_type_id FROM drugitems d  WHERE  d.icode = op.icode limit 1),
    (SELECT nd.nhso_adp_type_id FROM nondrugitems nd  WHERE  nd.icode = op.icode limit 1)),'') in ('11'),
    IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,
            IF((SELECT COUNT(*) FROM ipt_order_no o1 WHERE o1.an = ovst.an AND o1.order_no = op.order_no AND o1.order_type = 'Hme' limit 1) >= 1,'2','1'),'2')  ,'') as USE_STATUS,

ROUND(IFNULL(SUM(CASE WHEN op.paidst in ('02') THEN IF(op.sum_price <> op.qty*op.unitprice ,op.qty*op.unitprice,op.sum_price) ELSE 0 END),0.00),2)  as TOTAL,

IF(pt.hipdata_code in ('UCS') AND
        (IFNULL(if(SUBSTRING(op.icode, 1, 1) = '1',(SELECT d.nhso_adp_type_id FROM drugitems d  WHERE  d.icode = op.icode limit 1),
        (SELECT nd.nhso_adp_type_id FROM nondrugitems nd  WHERE  nd.icode = op.icode limit 1)),'') in ('3') OR TRIM(op.an) = '' or op.an is NULL)
    AND (SELECT  COUNT(*) FROM opitemrece WHERE opitemrece.an = ovst.an AND opitemrece.icode in ('zz')) >= 1,
    IFNULL((ifnull((select FLOOR(sum(admit_hour)/24) from ipt_ward_stat where an  = ovst.an ),0) + 
                 if((select MOD(sum(admit_hour), 24) from ipt_ward_stat where an  = ovst.an ) >= 6 ,1,0)) ,'0'),
    '') as QTYDAY,


IFNULL((SELECT  lab_items.tmlt_code from lab_items WHERE lab_items.icode = op.icode ORDER BY lab_items.tmlt_code DESC  limit 1) ,'') as TMLTCODE,
IF((SELECT  COUNT(*) from nondrugitems nd WHERE nd.icode = op.icode 
        AND  nd.billcode in ('36590','36591','36592','36593','36594','36595','36596','36597','36598','36599')   limit 1) >= 1,
    IF( (SELECT COUNT(*) from lab_head 
                    LEFT OUTER JOIN lab_order on lab_order.lab_order_number = lab_head.lab_order_number 
                    LEFT OUTER JOIN lab_items on lab_items.lab_items_code = lab_order.lab_items_code
                    LEFT OUTER JOIN nondrugitems nondrug on nondrug.icode = lab_items.icode
                    WHERE lab_head.vn in ( ovst.vn, ovst.an) AND  lab_items.icode = op.icode  AND lab_head.order_date = op.rxdate
                    and lab_order.lab_order_result  in ('Negative','ไม่พบสารพันธุกรรมของเชื้อไวรัสโคโรนา 2019 (SARS-COV-2)') LIMIT 1) >= 1,'1','0')
    ,'') as STATUS1,

'' as BI,
IFNULL(s1.nhso_code,'') as CLINIC,
'2' as ITEMSRC,
IFNULL(IF(trim((SELECT doctor.licenseno FROM opduser LEFT OUTER JOIN doctor ON doctor.`code` = opduser.doctorcode WHERE opduser.loginname = op.staff)) <> '',
     (SELECT doctor.licenseno FROM opduser LEFT OUTER JOIN doctor ON doctor.`code` = opduser.doctorcode WHERE opduser.loginname = op.staff),
    IF((SELECT COUNT(*) FROM ipt  WHERE  ovst.an = ipt.an ) > 0,
     (SELECT doctor.licenseno FROM ipt LEFT OUTER JOIN doctor ON doctor.`code` = ipt.dch_doctor WHERE ipt.an = ovst.an),
         (SELECT doctor.licenseno FROM vn_stat LEFT OUTER JOIN doctor ON doctor.`code` = vn_stat.dx_doctor WHERE vn_stat.vn = ovst.vn))),'')  as PROVIDER,
IFNULL((SELECT a.preg_no from person_anc a left outer join person p1 on p1.person_id = a.person_id   
 WHERE trim(p1.cid) = p.cid AND ovst.vstdate BETWEEN a.lmp AND a.labor_date  LIMIT 1),'') as GRAVIDA,
'' as GA_WEEK,'' as DCIP,
IFNULL((SELECT IFNULL(DATE_FORMAT(a.lmp, '%Y%m%d'),'')  from person_anc a left outer join person p1 on p1.person_id = a.person_id   
 WHERE trim(p1.cid) = p.cid AND ovst.vstdate BETWEEN a.lmp AND a.labor_date  LIMIT 1),'')  as LMP

FROM ovst 
LEFT OUTER JOIN patient p on p.hn = ovst.hn
LEFT OUTER JOIN opitemrece op ON (op.vn = ovst.vn  AND op.vn <> '') or (op.an = ovst.an AND  ovst.an <> '' AND  op.an <> '')
LEFT OUTER JOIN pttype pt on pt.pttype = ovst.pttype
LEFT OUTER JOIN nondrugitems nd ON nd.icode = op.icode
LEFT OUTER JOIN drugitems d ON d.icode = op.icode
LEFT OUTER JOIN spclty s1 on s1.spclty = ovst.spclty
WHERE ovst.vn = '$vn'
#AND op.qty > 0
#AND NOT EXISTS (SELECT * FROM drugitems d  WHERE  d.icode = op.icode)
#GROUP BY op.icode,op.rxdate
GROUP BY op.icode
#ORDER BY op.income,op.icode";
             $get_adp_by_vn = $connect_his->select($sql); 
             if($get_adp_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_adp_by_vn),true) );

               
                foreach ($data as $key => $value) {
                    
                    


                    $mytext .=   $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['DATEOPD'].'|'.
                    $value['TYPE'].'|'.
                    $value['CODE'].'|'.
                    $value['QTY'].'|'.
                    $value['RATE'].'|'.
                    $value['SEQ'].'|'.
                    $value['CAGCODE'].'|'.
                    $value['DOSE'].'|'.
                    $value['CA_TYPE'].'|'.
                    $value['SERIALNO'].'|'.
                    $value['TOTCOPAY'].'|'.
                    $value['USE_STATUS'].'|'.
                    $value['TOTAL'].'|'.
                    $value['QTYDAY'].'|'.
                    $value['TMLTCODE']."|||||||||\n";
                }
              


                
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/ADP.txt',$mytext);

    }

    private function generate_lvd_sort_by($GetDataVn,$dateset){
        $mytext = "SEQLVD|AN|DATEOUT|TIMEOUT|DATEIN|TIMEIN|QTYDAY\n";
        Storage::disk('public_claim')->put($dateset.'/LVD.txt',$mytext);
    }
    private function generate_dru_sort_by($GetDataVn,$dateset){
         $connect_his = DB::connection('his_readonly');

         $mytext = "HCODE|HN|AN|CLINIC|PERSON_ID|DATE_SERV|DID|DIDNAME|AMOUNT|DRUGPRICE|DRUGCOST|DIDSTD|UNIT|UNIT_PACK|SEQ|DRUGREMARK|PA_NO|TOTCOPAY|USE_STATUS|TOTAL|SIGCODE|SIGTEXT|PROVIDER\n";
         if($GetDataVn != null){
            foreach ($GetDataVn as $keys => $rs) {
             $vn =  $rs['vn'];       
             $sql = "SELECT '10694' as HCODE,ovst.hn as HN,IFNULL(ovst.an,'') as AN,ovst.spclty as CLINIC,v.cid as PERSON_ID,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE_SERV
,o.icode as DID,concat(dg.name,' ',dg.strength) as DIDNAME,o.qty as AMOUNT,ROUND(o.unitprice,2) as DRUGPRICE,ROUND(o.cost,2) as DRUGCOST,dg.did as DIDSTD
,dg.units as UNIT,concat(dg.packqty,'x',dg.units) as UNIT_PACK,ovst.vn as SEQ
,concat(if(opn.presc_reason <> '' and opn.presc_reason_2 <> '',concat(opn.presc_reason,', ',opn.presc_reason_2),if(opn.presc_reason <> '',concat(opn.presc_reason),''))) as DRUGREMARK,'' as PA_NO,if(o.paidst = '03',ROUND(o.sum_price,2),'') as TOTCOPAY,'2' as USE_STATUS,if(o.paidst = '03','', ROUND(o.sum_price,2)) as TOTAL,IFNULL(o.drugusage,o.sp_use) as SIGCODE,IFNULL(concat(d.name1,d.name2,d.name3),concat(s.name1,s.name2,s.name3)) as SIGTEXT,'' as PROVIDER 
from ovst 
LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
LEFT OUTER JOIN drugitems dg on dg.icode = o.icode
LEFT OUTER JOIN ovst_presc_ned opn on opn.icode = o.icode and opn.vn = o.vn 
LEFT OUTER JOIN drugusage d on d.drugusage=o.drugusage
LEFT OUTER JOIN sp_use s on s.sp_use=o.sp_use
WHERE ovst.vn = '$vn'
and  o.income in ('03','04','80','21')
AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)

UNION

SELECT '10694' as HCODE,ovst.hn as HN,IFNULL(ovst.an,'') as AN,ovst.spclty as CLINIC,p.cid as PERSON_ID,DATE_FORMAT( o.vstdate, '%Y%m%d') as DATE_SERV
,o.icode as DID,concat(dg.name,' ',dg.strength) as DIDNAME,o.qty as AMOUNT,ROUND(o.unitprice,2) as DRUGPRICE,ROUND(o.cost,2) as DRUGCOST,dg.did as DIDSTD
,dg.units as UNIT,concat(dg.packqty,'x',dg.units) as UNIT_PACK,IFNULL(ovst.an,'') as SEQ
,concat(if(opn.presc_reason <> '' and opn.presc_reason_2 <> '',concat(opn.presc_reason,', ',opn.presc_reason_2),if(opn.presc_reason <> '',concat(opn.presc_reason),''))) as DRUGREMARK,'' as PA_NO,if(o.paidst = '03',ROUND(o.sum_price,2),'') as TOTCOPAY,if(o.item_type='H','2','1') as USE_STATUS,if(o.paidst = '03','', ROUND(o.sum_price,2)) as TOTAL,IFNULL(o.drugusage,o.sp_use) as SIGCODE,IFNULL(concat(d.name1,d.name2,d.name3),concat(s.name1,s.name2,s.name3)) as SIGTEXT,'' as PROVIDER 
from ovst 
LEFT OUTER JOIN an_stat a on a.an = ovst.an
LEFT OUTER JOIN patient p on p.hn = ovst.hn
LEFT OUTER JOIN opitemrece o on o.an = ovst.an
LEFT OUTER JOIN drugitems dg on dg.icode = o.icode
LEFT OUTER JOIN ovst_presc_ned opn on opn.icode = o.icode and opn.vn = o.an 
LEFT OUTER JOIN drugusage d on d.drugusage=o.drugusage
LEFT OUTER JOIN sp_use s on s.sp_use=o.sp_use
WHERE ovst.vn = '$vn'
and  o.income in ('03','04','80','21')
and o.qty <> '0'";
             $get_dru_by_vn = $connect_his->select($sql); 
             if($get_dru_by_vn != null){
                
                $data =  ( json_decode(json_encode($get_dru_by_vn),true) );

               
                foreach ($data as $key => $value) {
                    
                


                    $mytext .=   $value['HCODE'].'|'.
                    $value['HN'].'|'.
                    $value['AN'].'|'.
                    $value['CLINIC'].'|'.
                    $value['PERSON_ID'].'|'.
                    $value['DATE_SERV'].'|'.
                    $value['DID'].'|'.
                    $value['DIDNAME'].'|'.
                    $value['AMOUNT'].'|'.
                    $value['DRUGPRICE'].'|'.
                    $value['DRUGCOST'].'|'.
                    $value['DIDSTD'].'|'.
                    $value['UNIT'].'|'.
                    $value['UNIT_PACK'].'|'.
                    $value['SEQ'].'|'.
                    $value['DRUGREMARK'].'|'.
                    $value['PA_NO'].'|'.
                    $value['TOTCOPAY'].'|'.
                    $value['USE_STATUS'].'|'.
                    $value['TOTAL'].'|'.
                    $value['SIGCODE'].'|'.
                    $value['SIGTEXT'].'|'.
                    $value['PROVIDER']."\n";
                }
              


                
             }
            }
        }
        Storage::disk('public_claim')->put($dateset.'/DRU.txt',$mytext);

    }

    // private function generate_dru_sort_by($GetDataVn,$dateset){
    //      $connect_his = DB::connection('his_readonly');

    //      $mytext = "HCODE|HN|AN|CLINIC|PERSON_ID|DATE_SERV|DID|DIDNAME|AMOUNT|DRUGPRICE|DRUGCOST|DIDSTD|UNIT|UNIT_PACK|SEQ|DRUGREMARK|PA_NO|TOTCOPAY|USE_STATUS|TOTAL|SIGCODE|SIGTEXT|PROVIDER\n";
    //      if($GetDataVn != null){
    //         foreach ($GetDataVn as $keys => $rs) {
    //          $vn =  $rs['vn'];       
    //          $sql = "SELECT '10694' as HCODE,ovst.hn as HN,IFNULL(ovst.an,'') as AN,ovst.spclty as CLINIC,v.cid as PERSON_ID,DATE_FORMAT( ovst.vstdate, '%Y%m%d') as DATE_SERV
    //             ,o.icode as DID,concat(dg.name,' ',dg.strength) as DIDNAME,o.qty as AMOUNT,FORMAT(o.unitprice,2) as DRUGPRICE,FORMAT(o.cost,2) as DRUGCOST,dg.did as DIDSTD
    //             ,dg.units as UNIT,concat(dg.packqty,'x',dg.units) as UNIT_PACK,ovst.vn as SEQ
    //             ,concat(if(opn.presc_reason <> '' and opn.presc_reason_2 <> '',concat(opn.presc_reason,', ',opn.presc_reason_2),if(opn.presc_reason <> '',concat(opn.presc_reason),''))) as DRUGREMARK,'' as PA_NO,if(o.paidst = '03',FORMAT(o.sum_price,2),'') as TOTCOPAY,'2' as USE_STATUS,if(o.paidst = '03','', FORMAT(o.sum_price,2)) as TOTAL,IFNULL(o.drugusage,o.sp_use) as SIGCODE,IFNULL(concat(d.name1,d.name2,d.name3),concat(s.name1,s.name2,s.name3)) as SIGTEXT,'' as PROVIDER 
    //             from ovst 
    //             LEFT OUTER JOIN vn_stat v on v.vn = ovst.vn
    //             LEFT OUTER JOIN opitemrece o on o.vn = ovst.vn
    //             LEFT OUTER JOIN drugitems dg on dg.icode = o.icode
    //             LEFT OUTER JOIN ovst_presc_ned opn on opn.icode = o.icode and opn.vn = o.vn 
    //             LEFT OUTER JOIN drugusage d on d.drugusage=o.drugusage
    //             LEFT OUTER JOIN sp_use s on s.sp_use=o.sp_use
    //             WHERE ovst.vn = '$vn'
    //             and  o.income in ('03','04','80','21')
    //             AND NOT EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)

    //             UNION

    //             SELECT '10694' as HCODE,ovst.hn as HN,IFNULL(ovst.an,'') as AN,ovst.spclty as CLINIC,p.cid as PERSON_ID,DATE_FORMAT( o.vstdate, '%Y%m%d') as DATE_SERV
    //             ,o.icode as DID,concat(dg.name,' ',dg.strength) as DIDNAME,o.qty as AMOUNT,FORMAT(o.unitprice,2) as DRUGPRICE,FORMAT(o.cost,2) as DRUGCOST,dg.did as DIDSTD
    //             ,dg.units as UNIT,concat(dg.packqty,'x',dg.units) as UNIT_PACK,IFNULL(ovst.an,'') as SEQ
    //             ,concat(if(opn.presc_reason <> '' and opn.presc_reason_2 <> '',concat(opn.presc_reason,', ',opn.presc_reason_2),if(opn.presc_reason <> '',concat(opn.presc_reason),''))) as DRUGREMARK,'' as PA_NO,if(o.paidst = '03',FORMAT(o.sum_price,2),'') as TOTCOPAY,if(o.item_type='H','2','1') as USE_STATUS,if(o.paidst = '03','', FORMAT(o.sum_price,2)) as TOTAL,IFNULL(o.drugusage,o.sp_use) as SIGCODE,IFNULL(concat(d.name1,d.name2,d.name3),concat(s.name1,s.name2,s.name3)) as SIGTEXT,'' as PROVIDER 
    //             from ovst 
    //             LEFT OUTER JOIN an_stat a on a.an = ovst.an
    //             LEFT OUTER JOIN patient p on p.hn = ovst.hn
    //             LEFT OUTER JOIN opitemrece o on o.an = ovst.an
    //             LEFT OUTER JOIN drugitems dg on dg.icode = o.icode
    //             LEFT OUTER JOIN ovst_presc_ned opn on opn.icode = o.icode and opn.vn = o.an 
    //             LEFT OUTER JOIN drugusage d on d.drugusage=o.drugusage
    //             LEFT OUTER JOIN sp_use s on s.sp_use=o.sp_use
    //             WHERE ovst.vn = '$vn'
    //             and  o.income in ('03','04','80','21')
    //             and o.qty <> '0'
    //             AND EXISTS (SELECT * FROM ipt  WHERE  ovst.an = ipt.an)";
    //          $get_dru_by_vn = $connect_his->select($sql); 
    //          if($get_dru_by_vn != null){
                
    //             $data =  ( json_decode(json_encode($get_dru_by_vn),true) );

               
    //             foreach ($data as $key => $value) {
                    
                


    //                 $mytext .=   $value['HCODE'].'|'.
    //                 $value['HN'].'|'.
    //                 $value['AN'].'|'.
    //                 $value['CLINIC'].'|'.
    //                 $value['PERSON_ID'].'|'.
    //                 $value['DATE_SERV'].'|'.
    //                 $value['DID'].'|'.
    //                 $value['DIDNAME'].'|'.
    //                 $value['AMOUNT'].'|'.
    //                 $value['DRUGPRICE'].'|'.
    //                 $value['DRUGCOST'].'|'.
    //                 $value['DIDSTD'].'|'.
    //                 $value['UNIT'].'|'.
    //                 $value['UNIT_PACK'].'|'.
    //                 $value['SEQ'].'|'.
    //                 $value['DRUGREMARK'].'|'.
    //                 $value['PA_NO'].'|'.
    //                 $value['TOTCOPAY'].'|'.
    //                 $value['USE_STATUS'].'|'.
    //                 $value['TOTAL'].'|'.
    //                 $value['SIGCODE'].'|'.
    //                 $value['SIGTEXT'].'|'.
    //                 $value['PROVIDER']."\n";
    //             }
              


                
    //          }
    //         }
    //     }
    //     Storage::disk('public_claim')->put($dateset.'/DRU.txt',$mytext);

    // }
  





 private function generate_ins(){
    $connect_his = DB::connection('his_readonly');
    $sql = "SELECT  o.hn AS HN , p.hipdata_code AS INSCL 
    , p.hipdata_pttype  AS SUBTYPE , pa.cid AS CID , REPLACE(vns.pttype_begin,'-','') AS DATEIN ,  REPLACE(vns.pttype_expire,'-','') AS DATEEXP 
    , o.hospmain AS HOSPMAIN , o.hospsub AS HOSPSUB 
    , '' AS GOVCODE , '' AS GOVNAME , '' AS PERMITNO , '' AS DOCNO , '' AS OWNRPID,  '' AS OWNNAME , '' AS AN 
    , o.vn AS SEQ 
    , '' AS SUBINSCL  , '' AS RELINSCL , '' AS HTYPE
    FROM ovst AS o
    LEFT OUTER JOIN pttype AS p ON p.pttype = o.pttype
    LEFT OUTER JOIN patient AS pa ON pa.hn = o.hn
    LEFT OUTER JOIN vn_stat AS vns ON o.vn = vns.vn
    WHERE o.vstdate BETWEEN '2022-09-01' AND  '2022-09-01'  AND p.hipdata_code = 'UCS'";
    $data_his_set = $connect_his->select($sql);

    $data_his_set_filter =  ( json_decode(json_encode($data_his_set),true) );
        // return $data_his_set_filter;
    $mytext = '';
    foreach ($data_his_set_filter as $key => $value) {  
        $mytext .=  $value['HN'].'|'.$value['INSCL'].'|'.$value['SUBTYPE'].'|'.$value['CID'].'|'.$value['DATEIN'].'|'.$value['DATEEXP'].'|'.$value['HOSPMAIN'].'|'.$value['HOSPSUB'].'|'.$value['GOVCODE'].'|'.$value['GOVNAME'].'|'.$value['PERMITNO'].'|'.$value['DOCNO'].'|'.$value['OWNRPID'].'|'.$value['OWNNAME'].'|'.$value['AN'].'|'.$value['SEQ'].'|'.$value['SUBINSCL'].'|'.$value['RELINSCL'].'|'.$value['HTYPE']."\n";


    }
    Storage::disk('public_claim')->put($dateset.'/INS.txt',$mytext);

}
private function generate_pat(){
    $connect_his = DB::connection('his_readonly');
    $sql = "SELECT  o.hn AS HN , p.hipdata_code AS INSCL 
    , p.hipdata_pttype  AS SUBTYPE , pa.cid AS CID , REPLACE(vns.pttype_begin,'-','') AS DATEIN ,  REPLACE(vns.pttype_expire,'-','') AS DATEEXP 
    , o.hospmain AS HOSPMAIN , o.hospsub AS HOSPSUB 
    , '' AS GOVCODE , '' AS GOVNAME , '' AS PERMITNO , '' AS DOCNO , '' AS OWNRPID,  '' AS OWNNAME , '' AS AN 
    , o.vn AS SEQ 
    , '' AS SUBINSCL  , '' AS RELINSCL , '' AS HTYPE
    FROM ovst AS o
    LEFT OUTER JOIN pttype AS p ON p.pttype = o.pttype
    LEFT OUTER JOIN patient AS pa ON pa.hn = o.hn
    LEFT OUTER JOIN vn_stat AS vns ON o.vn = vns.vn
    WHERE o.vstdate BETWEEN '2022-09-01' AND  '2022-09-01'  AND p.hipdata_code = 'UCS'
        /*
        AND  o.vn IN ('650817070212',
        '650816201229',
        '650807053607',
        '650801013030')
        AND p.hipdata_code = 'UCS'*/";
        $data_his_set = $connect_his->select($sql);

        $data_his_set_filter =  ( json_decode(json_encode($data_his_set),true) );
        // return $data_his_set_filter;
        $mytext = '';
        foreach ($data_his_set_filter as $key => $value) {  
            $mytext .=  $value['HN'].'|'.$value['INSCL'].'|'.$value['SUBTYPE'].'|'.$value['CID'].'|'.$value['DATEIN'].'|'.$value['DATEEXP'].'|'.$value['HOSPMAIN'].'|'.$value['HOSPSUB'].'|'.$value['GOVCODE'].'|'.$value['GOVNAME'].'|'.$value['PERMITNO'].'|'.$value['DOCNO'].'|'.$value['OWNRPID'].'|'.$value['OWNNAME'].'|'.$value['AN'].'|'.$value['SEQ'].'|'.$value['SUBINSCL'].'|'.$value['RELINSCL'].'|'.$value['HTYPE']."\n";


        }
        Storage::disk('public_claim')->put($dateset.'/INS.txt',$mytext);

    }

}
