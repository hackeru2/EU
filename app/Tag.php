<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function tags()
    {
        return $this->belongsToMany('App\User');
    }
}
