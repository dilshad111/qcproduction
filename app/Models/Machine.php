<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['name', 'type', 'department', 'status'];

    public function trackings()
    {
        return $this->hasMany(ProductionTracking::class);
    }
}
