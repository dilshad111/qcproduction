<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartonType extends Model
{
    protected $fillable = [
        'name',
        'standard_code',
    ];
}
