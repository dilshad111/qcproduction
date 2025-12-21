<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs'; // Explicitly set table name if needed
    protected $fillable = ['name', 'department', 'role', 'status'];

    public function trackings()
    {
        return $this->hasMany(ProductionTracking::class);
    }
}
