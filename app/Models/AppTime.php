<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTime extends Model
{
    protected $table = 'apptime';

    protected $fillable = [
        'id',
        'appName',
        'appTime',
        'sessionValue',
        'created_at',
        'updated_at',
    ];
}
