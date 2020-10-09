<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    protected $table = 'session';

    protected $fillable = [
        'id',
        'mac',
        'timeType',
        'time',
        'date',
        'sessionValue',
        'timestamp',
        'created_at',
        'updated_at',
        'user_id'
    ];
}
