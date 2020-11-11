<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTime extends Model
{
    protected $table = 'apptime';

    protected $foreignKey = 'sessionValue';

    public $fillable = [
        'id',
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

}
