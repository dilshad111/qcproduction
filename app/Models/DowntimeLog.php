<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowntimeLog extends Model
{
    protected $fillable = [
        'corrugation_log_id', 'reason', 'start_time', 'end_time', 'duration_minutes'
    ];

    protected $dates = ['start_time', 'end_time'];

    public function corrugationLog()
    {
        return $this->belongsTo(CorrugationLog::class);
    }
}
