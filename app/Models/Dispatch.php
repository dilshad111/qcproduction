<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = [
        'job_issue_id', 'dispatch_date', 'qty_dispatched', 'vehicle_no', 'notes'
    ];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }
}
