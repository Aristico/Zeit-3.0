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

}
