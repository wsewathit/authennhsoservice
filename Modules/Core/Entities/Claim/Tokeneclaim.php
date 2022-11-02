<?php

namespace Modules\Core\Entities\Claim;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 	


class Tokeneclaim extends Model
{
  	protected $connection = 'mysql_239_claim';
    protected $table = 'tb_token_eclaim';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    
        
}








