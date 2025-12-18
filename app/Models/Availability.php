<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'labourer_id',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function labourer(): BelongsTo
    {
        return $this->belongsTo(Labourer::class);
    }
}
