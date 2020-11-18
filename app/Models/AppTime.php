<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTime extends Model
{
    protected $table = 'apptime';

        /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $foreignKey = 'sessionValue';

    public $fillable = [
        'appName',
        'appTime',
        'appTimeSpec',
        'user_id',
        'private',
        'created_at',
        'updated_at'
    ];


    public function sessions()
    {
        return $this->belongsTo('App\Models\Session', 'sessionValue', 'sessionValue');
    }

    public function hours()
    {
        return $this->hasMany('App\Models\ApptimeDetailsHr', 'apptime_id', 'id');
    }


}
