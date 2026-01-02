<?php

namespace App\Models;

use App\Enums\MonthEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'source_id',
        'date',
        'nominal',
        'notes',
        'month',
        'year'
    ];

    public function casts()
    {
        return[
            'month' => MonthEnum::class,
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function source(): BelongsTo {
        return $this->belongsTo(Budget::class, 'source_id', 'id');
    }
}
