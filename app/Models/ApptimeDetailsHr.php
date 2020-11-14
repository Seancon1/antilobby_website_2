<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApptimeDetailsHr extends Model
{
    protected $table = 'apptime_details_hr';

        /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'id';

    protected $foreignKey = ['apptime_id', 'apptime_details_min_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public $fillable = [
        'hour',
    ];


    public function app()
    {
        return $this->belongsTo('App\Models\AppTime');
    }

    public function minutes()
    {
        return $this->hasMany('App\Models\ApptimeDetailsMin');
    }



}
