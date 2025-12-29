<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCardPiece extends Model
{
    protected $fillable = [
        'job_card_id',
        'piece_number',
        'piece_name',
        'length',
        'width',
        'height',
        'deckle_size',
        'sheet_length',
        'ply_type',
        'ups',
        'print_colors',
        'printing_data',
        'packing_bundle_qty',
        'packing_type',
        'slitting_creasing',
        'die_sketch_path',
        'corrugation_instruction',
        'printing_instruction',
        'finishing_instruction'
    ];

    protected $casts = [
        'printing_data' => 'array',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function layers()
    {
        return $this->hasMany(JobCardLayer::class, 'piece_id');
    }
}
