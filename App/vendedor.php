<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vendedor extends Model
{
    protected $table = "vendedores";
	
    protected $fillable = [
        'id','nome','email','peso','recebeu','empresarial',
    ];

}
