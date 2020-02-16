<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{


    protected $fillable = ["ccm2_Id" ,"chilrdren_ids","description","name","parent",];
    // protected $casts = [
    //     'chilrdren_ids' => 'array',
    // ];

}
