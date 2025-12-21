<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class JobIssue extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'issue_no', 'job_card_id', 'customer_id', 'po_number', 'order_qty_cartons',
        'required_sheet_qty', 'status'
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reels()
    {
        return $this->hasMany(ProductionReel::class);
    }

    public function tracking()
    {
        return $this->hasMany(ProductionTracking::class);
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }
}
