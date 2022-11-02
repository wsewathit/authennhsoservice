<?php

namespace Modules\Core\Entities\BackendSysTem;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class User extends Model
{
  	protected $connection = 'mysql_backend';
    protected $table = 'tb_user'; 
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
}
