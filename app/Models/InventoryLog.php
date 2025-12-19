<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryLog extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['job_issue_id', 'qty_in', 'location', 'date', 'remarks'];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }
}
