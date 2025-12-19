<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class ProductionTracking extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'production_tracking';

    protected $fillable = [
        'job_issue_id', 'process_stage', 'produced_qty', 'status', 'qc_approved', 'remarks',
        'machine_id', 'staff_id', 'date'
    ];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
