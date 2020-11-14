<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApptimeDetailsMin extends Model
{
    protected $table = 'apptime_details_min';

        /**
     * The primary key associated with the table.
     *
     * @var int
     */
    protected $primaryKey = 'id';

    protected $foreignKey = 'apptime_details_hr_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public $fillable = [
        'minute',
        'count'
    ];


    public function Hr()
    {
        return $this->belongsTo('App\Models\ApptimeDetailsHr');
    }

}
