<?php

namespace App;

use Carbon\Carbon;
use DateTime;
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
        'start_balance'
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

        /* Ermittelt den Aktuellen Zeit-Plan. Bez. den neuesten.
         * Ist schon eine Änderung in dieser Woche erfolgt und somit für den nächsten Montag ein neuer Zeit-Plan
         * geplant, wird dieser ermittelt.
         * */

        $lastversion = $this->schedules()->orderBy('version', 'desc')->first();
        return $this->schedules()->where('version', '=', $lastversion->version)->get();

    }

    public function currentScheduleToday() {

        $now = new DateTime('now');
        $schedule = $this->currentSchedule()->where('day', $now->format('N'))->first();
        return $schedule;

    }

    public function scheduleByDate($date) {

        $day = new Carbon($date);
        $schedule = $this->schedules()->where([['day', $day->format('N')],['valid_from','<=','date']])->first();
        return $schedule;

    }

    public function currentHours () {

        $currentversion = $this->schedules()->where('valid_from', '<', new DateTime())->orderBy('version', 'desc')->first();
        $schedule = $this->schedules()->where('version', '=', $currentversion->version)->get();

        $hours = 0;

        foreach ($schedule as $day) {
            $hours += $day->regularHours();
        }

        return $hours;

    }
}
