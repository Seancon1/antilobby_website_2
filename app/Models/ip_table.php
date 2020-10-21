<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ip_table extends Model
{
    public $timestamps = false;
    protected $table = 'ip_table';

    protected $fillable = [
        'id',
        'IP',
        'sessionValue',
    ];

}
