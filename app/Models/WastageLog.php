<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WastageLog extends Model
{
    protected $fillable = [
        'corrugation_log_id', 'type', 'quantity', 'unit', 'staff_id', 'reason'
    ];

    public function corrugationLog()
    {
        return $this->belongsTo(CorrugationLog::class);
    }
    
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
