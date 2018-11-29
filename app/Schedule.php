<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [

        'user_id',
        'day',
        'begin',
        'end',
        'break',
        'valid_from',
        'version'

    ];

    public function regularHours () {

        $begin = new Carbon($this->begin);
        $difference = round((($begin->diffInSeconds(new Carbon($this->end)) - $this->break*60)/60/60)*4)/4;

        return $difference;

    }

}
