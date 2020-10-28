<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    //
    protected $table = 'session';

    protected $primaryKey = 'sessionValue';

    protected $fillable = [
        'id',
        'mac',
        'timeType',
        'time',
        'date',
        'timestamp',
        'created_at',
        'updated_at',
        'user_id',
        'private',
    ];

    public function apps()
    {
        return $this->hasMany('App\Models\AppTime', 'sessionValue');
    }
}
