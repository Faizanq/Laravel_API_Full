<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;


    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verify_email_token',
        'verified',
        'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verify_email_token'
    ];

    public function setNameAttribute($name){
        $this->attributes['name'] = strtolower($name);
    }
    public function getNameAttribute($name){
        return ucwords($name);
    }
    public function setEmailAttribute($email){
        $this->attributes['email'] = strtolower($email);
    }

    public function isVerified(){
        // dd($this->verified , User::VERIFIED_USER,$this->verification_token);
        $this->verified == User::VERIFIED_USER;
    }
    public function isAdmin(){
        $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return str_random(40);
    }

    public function getPosts(){
        return $this->belongsToMany(\App\Post::class);
    }
}
