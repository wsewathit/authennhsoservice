<?php

namespace Modules\Core\Entities\PatientNotify;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	



class Patientnotify extends Model
{
  	protected $connection = 'mysql_244_noti';
    protected $table = 'tb_patient';
    protected $primaryKey = 'patient_id';
    protected $fillable = [

    ];

    
   
}







