<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;

class JobCard extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'job_no', 'version', 'is_active', 'customer_id', 'carton_type_id', 'pieces_count', 'item_name', 'item_code', 'uom',
        'length', 'width', 'height', 'deckle_size', 'sheet_length', 'ups',
        'ply_type', 'slitting_creasing', 'print_colors', 'printing_data', 'pasting_type',
        'staple_details', 'special_details', 'process_type', 'packing_bundle_qty', 'packing_type', 'remarks',
        'corrugation_instruction', 'printing_instruction', 'finishing_instruction', 'revision_note'
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
        return $this->hasMany(JobCardLayer::class)->whereNull('piece_id')->orderBy('layer_order');
    }

    public function pieces()
    {
        return $this->hasMany(JobCardPiece::class)->orderBy('piece_number');
    }
}
