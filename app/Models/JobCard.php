<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class JobCard extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'job_no', 'customer_id', 'carton_type_id', 'item_name', 'item_code', 'uom',
        'length', 'width', 'height', 'deckle_size', 'sheet_length', 'ups',
        'ply_type', 'slitting_creasing', 'print_colors', 'printing_data', 'pasting_type',
        'staple_details', 'special_details', 'process_type', 'packing_bundle_qty', 'remarks'
    ];

    protected $casts = [
        'printing_data' => 'array',
        'special_details' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cartonType()
    {
        return $this->belongsTo(CartonType::class);
    }

    public function layers()
    {
        return $this->hasMany(JobCardLayer::class)->orderBy('layer_order');
    }
}
