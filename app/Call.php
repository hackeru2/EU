<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $guarded = [];
    protected $casts = [
        'new_tags' => 'array',
    ];
}
