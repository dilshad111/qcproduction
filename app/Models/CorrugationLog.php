<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorrugationLog extends Model
{
    protected $fillable = [
        'job_issue_id', 'machine_id', 'machine_id_2', 'staff_id',
        'start_time', 'end_time', 'total_sheets_produced', 'avg_speed_mpm'
    ];

    protected $dates = ['start_time', 'end_time'];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }

    public function downtimes()
    {
        return $this->hasMany(DowntimeLog::class);
    }

    public function wastages()
    {
        return $this->hasMany(WastageLog::class);
    }

    public function timeSessions()
    {
        return $this->hasMany(CorrugationTimeSession::class);
    }
}
