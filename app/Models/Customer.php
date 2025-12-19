<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class Customer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
