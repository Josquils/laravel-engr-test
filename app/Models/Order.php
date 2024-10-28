<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'hmo_code',
        'provider_name',
        'batch_id',
        'payload',
        'encounter_date',
        'total'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function hmo(): BelongsTo
    {
        return $this->belongsTo(Hmo::class, 'hmo_code', 'code');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
