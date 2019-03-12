<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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

        Log::debug('EntryModel - calculateHours - Parameters: start ' . $start . ' end: ' . $end . ' break: ' . $break);

        $begin = new Carbon($start);
        $difference = round((($begin->diffInSeconds(new Carbon($end)) - $break*60)/60/60)*4)/4;

        Log::debug('EntryModel - calculateHours - calculatet Values: begin: ' . $begin . ' difference ' . $difference);

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

}
