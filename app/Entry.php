<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{

    protected $fillable = [
        'user_id',
        'date',
        'begin',
        'end',
        'break',
        'balance',
        'regular_hours',
        'schedule_version',
        'comment'
    ];

}
