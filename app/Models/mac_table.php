<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mac_table extends Model
{
    public $timestamps = false;
    protected $table = 'mac_table';

    protected $fillable = [
        'mac',
        'sessionValue',

    ];

}
