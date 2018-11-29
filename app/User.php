<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'name',
        'email',
        'identifier',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {

        return $this->belongsTo('App/Role');

    }

    public function entries() {

        return $this->hasMany('App\Entry');

    }

    public function schedules() {

        return $this->hasMany('App\Schedule');

    }

    public function currentSchedule () {

        $lastversion = $this->schedules()->orderBy('version', 'desc')->first();
        return $this->schedules()->where('version', '=', $lastversion->version)->get();

    }
}
