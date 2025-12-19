<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionTracking extends Model
{
    protected $table = 'production_tracking';

    protected $fillable = [
        'job_issue_id', 'process_stage', 'status', 'qc_approved', 'remarks'
    ];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }
}
