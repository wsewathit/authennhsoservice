<?php

namespace Modules\Claim\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Claim\Tokeneclaim;
use DB;
class ClaimGenApiController extends Controller
{

	public function CurlApiEclaim($path = null){
		$Tokeneclaim = Tokeneclaim::orderBy('id','DESC')->first();
		if($Tokeneclaim != null){
			$GetToken = $Tokeneclaim->toArray();
			$token = $GetToken['token_value'];
		}else{
			return ['status'=>false,'message'=>'Hasn`t Token '];
		}
		
		$CreateIns = $this->CreateIns($path);
		$CreatePat = $this->CreatePat($path);
		$CreateOpd = $this->CreateOpd($path);
		$CreateOrf = $this->CreateOrf($path);	
		$CreateOdx = $this->CreateOdx($path);
		$CreateOop = $this->CreateOop($path);
		$CreateIpd = $this->CreateIpd($path);
		$CreateIrf = $this->CreateIrf($path);
		$CreateIdx = $this->CreateIdx($path);
		$CreateIop = $this->CreateIop($path);
		$CreateCht = $this->CreateCht($path);
		$CreateCha = $this->CreateCha($path);
		$CreateAer = $this->CreateAer($path);
		$CreateAdp = $this->CreateAdp($path);
		$CreateLvd = $this->CreateLvd($path);
		$CreateDru = $this->CreateDru($path);
		$curl = curl_init();

			curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://nhsoapi.nhso.go.th/FMU/ecimp/v1/send',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
		  "fileType": "txt",
		  "maininscl": "UCS",
		  "importDup": true,
		  "assignToMe": false,
		  "dataTypes": [
		    "OP"
		  ],
		  "opRefer": false,
		  "file": {
		    "ins": {
		      "blobName": "INS.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateIns->blob.'",
		      "size": '.$CreateIns->size.',
		      "encoding": "UTF-8"
		    },
		    "pat": {
		      "blobName": "PAT.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreatePat->blob.'",
		      "size": '.$CreatePat->size.',
		      "encoding": "UTF-8"
		    },
		    "opd": {
		      "blobName": "OPD.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateOpd->blob.'",
		      "size": '.$CreateOpd->size.',
		      "encoding": "UTF-8"
		    },
		    "orf": {
		      "blobName": "ORF.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateOrf->blob.'",
		      "size": '.$CreateOrf->size.',
		      "encoding": "UTF-8"
		    },
		    "odx": {
		      "blobName": "ODX.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateOdx->blob.'",
		      "size": '.$CreateOdx->size.',
		      "encoding": "UTF-8"
		    },
		    "oop": {
		      "blobName": "OOP.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateOop->blob.'",
		      "size": '.$CreateOop->size.',
		      "encoding": "UTF-8"
		    },
		    "ipd": {
		      "blobName": "IPD.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateIpd->blob.'",
		      "size": '.$CreateIpd->size.',
		      "encoding": "UTF-8"
		    },
		    "irf": {
		      "blobName": "IRF.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateIrf->blob.'",
		      "size": '.$CreateIrf->size.',
		      "encoding": "UTF-8"
		    },
		    "idx": {
		      "blobName": "IDX.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateIdx->blob.'",
		      "size": '.$CreateIdx->size.',
		      "encoding": "UTF-8"
		    },
		    "iop": {
		      "blobName": "IOP.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateIop->blob.'",
		      "size": '.$CreateIop->size.',
		      "encoding": "UTF-8"
		    },
		    "cht": {
		      "blobName": "CHT.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateCht->blob.'",
		      "size": '.$CreateCht->size.',
		      "encoding": "UTF-8"
		    },
		    "cha": {
		      "blobName": "CHA.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateCha->blob.'",
		      "size": '.$CreateCha->size.',
		      "encoding": "UTF-8"
		    },
		    "aer": {
		      "blobName": "AER.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateAer->blob.'",
		      "size": '.$CreateAer->size.',
		      "encoding": "UTF-8"
		    },
		    "adp": {
		      "blobName": "ADP.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateAdp->blob.'",
		      "size": '.$CreateAdp->size.',
		      "encoding": "UTF-8"
		    },
		    "lvd": {
		      "blobName": "LVD.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateLvd->blob.'",
		      "size": '.$CreateLvd->size.',
		      "encoding": "UTF-8"
		    },
		    "dru": {
		      "blobName": "DRU.txt",
		      "blobType": "text/plain",
		      "blob": "'.$CreateDru->blob.'",
		      "size": '.$CreateDru->size.',
		      "encoding": "UTF-8"
		    },
		    "lab": null
		  }
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer '.$token,
		    'Content-Type: application/json',
		    'Cookie: TS01e88bc2=013bd252cb2f6112b8717a0679b9fffe1aba9b8092a041d7331cad4ff6eeb8c7546b028925f189478a878786743da36ae6321a5bd7'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
	public function GenerateData($path = null){
		set_time_limit(0);
		$CreateIns = $this->CreateIns($path);
		$CreatePat = $this->CreatePat($path);
		$CreateOpd = $this->CreateOpd($path);
		$CreateOrf = $this->CreateOrf($path);	
		$CreateOdx = $this->CreateOdx($path);
		$CreateOop = $this->CreateOop($path);
		$CreateIpd = $this->CreateIpd($path);
		$CreateIrf = $this->CreateIrf($path);
		$CreateIdx = $this->CreateIdx($path);
		$CreateIop = $this->CreateIop($path);
		$CreateCht = $this->CreateCht($path);
		$CreateCha = $this->CreateCha($path);
		$CreateAer = $this->CreateAer($path);
		$CreateAdp = $this->CreateAdp($path);
		$CreateLvd = $this->CreateLvd($path);
		$CreateDru = $this->CreateDru($path);

		$file = new class{};
		$file->ins = $CreateIns;
		$file->pat = $CreatePat;
		$file->opd = $CreateOpd;
		$file->orf = $CreateOrf;
		$file->odx = $CreateOdx;
		$file->oop = $CreateOop;
		$file->ipd = $CreateIpd;
		$file->irf = $CreateIrf;
		$file->idx = $CreateIdx;
		$file->iop = $CreateIop;
		$file->cht = $CreateCht;
		$file->cha = $CreateCha;
		$file->aer = $CreateAer;
		$file->adp = $CreateAdp;
		$file->lvd = $CreateLvd;
		$file->dru = $CreateDru;


		$file->lab = null;


    	$data_post = [
    		'fileType'=>'txt',
    		'maininscl'=>'UCS',
    		'importDup'=>true,
    		'assignToMe'=>true,
    		'dataTypes'=>[
    			// "IP",
    			"OP"
    		],
    		"opRefer"=>false,
    		"file"=>$file
    	];
	
	
		return $data_post;
	}
	private function CreateIns($path = null){
		$result_ins = Storage::disk('public_claim')->get($path.'/INS.txt');
		$size_ins = Storage::disk('public_claim')->size($path.'/INS.txt');
		$file = new class{};
		$file->blobName = 'INS.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result_ins);		
		$file->size = $size_ins;
		$file->encoding = 'UTF-8';
		return $file;
	}

	private function CreatePat($path = null){
		$result = Storage::disk('public_claim')->get($path.'/PAT.txt');
		$size = Storage::disk('public_claim')->size($path.'/PAT.txt');
		$file = new class{};
		$file->blobName = 'PAT.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateOpd($path = null){
		$result = Storage::disk('public_claim')->get($path.'/OPD.txt');
		$size = Storage::disk('public_claim')->size($path.'/OPD.txt');
		$file = new class{};
		$file->blobName = 'OPD.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateOrf($path = null){
		$result = Storage::disk('public_claim')->get($path.'/ORF.txt');
		$size = Storage::disk('public_claim')->size($path.'/ORF.txt');
		$file = new class{};
		$file->blobName = 'ORF.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file; 
	}private function CreateOdx($path = null){
		$result = Storage::disk('public_claim')->get($path.'/ODX.txt');
		$size = Storage::disk('public_claim')->size($path.'/ODX.txt');
		$file = new class{};
		$file->blobName = 'ODX.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateOop($path = null){
		$result = Storage::disk('public_claim')->get($path.'/OOP.txt');
		$size = Storage::disk('public_claim')->size($path.'/OOP.txt');
		$file = new class{};
		$file->blobName = 'OOP.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateIpd($path = null){
		$result = Storage::disk('public_claim')->get($path.'/IPD.txt');
		$size = Storage::disk('public_claim')->size($path.'/IPD.txt');
		$file = new class{};
		$file->blobName = 'IPD.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateIrf($path = null){
		$result = Storage::disk('public_claim')->get($path.'/IRF.txt');
		$size = Storage::disk('public_claim')->size($path.'/IRF.txt');
		$file = new class{};
		$file->blobName = 'IRF.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateIdx($path = null){
		$result = Storage::disk('public_claim')->get($path.'/IDX.txt');
		$size = Storage::disk('public_claim')->size($path.'/IDX.txt');
		$file = new class{};
		$file->blobName = 'IDX.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateIop($path = null){
		$result = Storage::disk('public_claim')->get($path.'/IOP.txt');
		$size = Storage::disk('public_claim')->size($path.'/IOP.txt');
		$file = new class{};
		$file->blobName = 'IOP.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateCht($path = null){
		$result = Storage::disk('public_claim')->get($path.'/CHT.txt');
		$size = Storage::disk('public_claim')->size($path.'/CHT.txt');
		$file = new class{};
		$file->blobName = 'CHT.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateAer($path = null){
		$result = Storage::disk('public_claim')->get($path.'/AER.txt');
		$size = Storage::disk('public_claim')->size($path.'/AER.txt');
		$file = new class{};
		$file->blobName = 'AER.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateAdp($path = null){
		$result = Storage::disk('public_claim')->get($path.'/ADP.txt');
		$size = Storage::disk('public_claim')->size($path.'/ADP.txt');
		$file = new class{};
		$file->blobName = 'ADP.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateLvd($path = null){
		$result = Storage::disk('public_claim')->get($path.'/LVD.txt');
		$size = Storage::disk('public_claim')->size($path.'/LVD.txt');
		$file = new class{};
		$file->blobName = 'LVD.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateCha($path = null){
		$result = Storage::disk('public_claim')->get($path.'/CHA.txt');
		$size = Storage::disk('public_claim')->size($path.'/CHA.txt');
		$file = new class{};
		$file->blobName = 'CHA.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}private function CreateDru($path = null){
		$result = Storage::disk('public_claim')->get($path.'/DRU.txt');
		$size = Storage::disk('public_claim')->size($path.'/DRU.txt');
		$file = new class{};
		$file->blobName = 'DRU.txt';
		$file->blobType = 'text/plain';
		$file->blob = base64_encode($result);		
		$file->size = $size;
		$file->encoding = 'UTF-8';
		return $file;
	}

}



