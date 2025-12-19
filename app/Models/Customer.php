<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact_no',
        'gst_no',
        'email',
        'optional_fields',
    ];

    protected $casts = [
        'optional_fields' => 'array',
    ];
}
