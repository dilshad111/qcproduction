<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ink extends Model
{
    protected $fillable = [
        'color_name',
        'color_code',
    ];
}
