<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $table = 'app_version';
    protected $foreignKey = 'version';

    protected $fillable = [
        'alias',
        'download_path',
        'notes',
        'created_at',
        'updated_at',
    ];

}
