<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $with = ['flags','profile'];
    protected $appends = array('keywords');

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
         
    }
    public function flags()
    {
        return $this->belongsToMany('App\Flag');
    }
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
    public function profileAndKeywords()
    {
        
         
         
    }
    public function getKeywordsAttribute()
    {
        
        $ccm2_ids =  explode("," , $this->profile()->select('keywords_ccm2_Ids')->first()->keywords_ccm2_Ids );
        return Keyword::whereIn('ccm2_id' , $ccm2_ids)->get()->values()   ;
    }
}
