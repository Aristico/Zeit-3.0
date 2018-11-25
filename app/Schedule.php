<?php

namespace App;

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

}
