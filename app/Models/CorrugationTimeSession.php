<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrugationTimeSession extends Model
{
    protected $fillable = [
        'corrugation_log_id',
        'session_start',
        'session_end',
        'duration_minutes',
        'notes'
    ];

    protected $dates = ['session_start', 'session_end'];

    public function corrugationLog()
    {
        return $this->belongsTo(CorrugationLog::class);
    }
}
