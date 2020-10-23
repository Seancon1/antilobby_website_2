<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTime extends Model
{
    protected $table = 'apptime';

    public $fillable = [
        'id',
        'appName',
        'appTime',
        'sessionValue',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
