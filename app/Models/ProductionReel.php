<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionReel extends Model
{
    protected $fillable = [
        'job_issue_id', 'reel_number', 'weight'
    ];

    public function jobIssue()
    {
        return $this->belongsTo(JobIssue::class);
    }
}
