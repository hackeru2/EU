<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    
    
    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function setKeywords_ccm2_IdsAttribue(){

         
    }
}
