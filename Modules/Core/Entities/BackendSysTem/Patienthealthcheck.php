<?php

namespace Modules\Core\Entities\BackendSystem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Patienthealthcheck extends Model
{
  	protected $connection = 'mysql_healthcheck';
    protected $table = 'tb_patient';
    protected $primaryKey = 'patient_id';
    protected $fillable = [

    ];

    const CREATED_AT = "patient_datecreate";
    const UPDATED_AT = "patient_dateupdate";
    
        
}







