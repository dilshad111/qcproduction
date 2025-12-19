<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class JobCardLayer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'job_card_id', 'layer_order', 'type', 'paper_name', 'gsm', 'flute_type'
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }
}
