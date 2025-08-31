<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'fname',
        'lname',
        'contact',
        'pic',
        'sex'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function getProfilePictureAttribute()
    {
        return $this->pic 
            ? asset("storage/{$this->pic}") 
            : asset('assets/dist/img/default-profile.png');
    }

    public function coordinator()
    {
        return $this->hasOne(Coordinator::class);
    }

    public function intern()
    {
        return $this->hasOne(Intern::class);
    }

    public function hte()
    {
        return $this->hasOne(Hte::class);
    }

}