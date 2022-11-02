<?php

namespace Modules\Claim\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Claim\Tokeneclaim;

use DB;
class AuthenController extends Controller
{

    public function index()
    {
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => 'https://nhsoapi.nhso.go.th/FMU/ecimp/v1/auth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)",
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "username":"6212957241912",
          "password":"a12345"
        }',
        CURLOPT_HTTPHEADER => [
          'Content-Type: application/json'
        ],
      ]);

      $response = curl_exec($curl);


      curl_close($curl);

      $decode = json_decode($response,true);
      // echo '<pre>';
      // print_r($decode);

      Tokeneclaim::insert([
        'token_value'=>$decode['token']
      ]);
      // exit;
      // echo $response;
    }

}
