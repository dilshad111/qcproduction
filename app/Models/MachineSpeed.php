<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineSpeed extends Model
{
    protected $fillable = [
        'speed_3ply',
        'speed_5ply',
    ];
}
