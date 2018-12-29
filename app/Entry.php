<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{

    protected $fillable = [
        'user_id',
        'date',
        'begin',
        'end',
        'break',
        'regular_hours',
        'actual_hours',
        'overtime',
        'balance',
        'schedule_version',
        'comment'
    ];

    public function calculateHours($start, $end, $break) {

        $begin = new Carbon($start);
        $difference = round((($begin->diffInSeconds(new Carbon($end)) - $break*60)/60/60)*4)/4;
        return $difference;

    }

    public function dateCarbon() {

        return new Carbon($this->date);

    }

    public function beginCarbon() {

        return new Carbon($this->date . ' ' . $this->begin);

    }

    public function endCarbon() {

        return new Carbon($this->date . ' ' .$this->end);

    }

    public function getBalanceAttribute ($value) {

        return number_format($value, 2, ',', '');

    }

    public function getRegularHoursAttribute ($value) {

        return number_format($value, 2, ',', '');

    }

    public function getActualHoursAttribute ($value) {

        return number_format($value, 2, ',', '');

    }

    public function getOvertimeAttribute ($value) {

        return number_format($value, 2, ',', '');

    }


//        'regular_hours',
//        'actual_hours',
//        'overtime',
//        'balance',
}
