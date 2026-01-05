<?php

namespace App\Models;

use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Payment extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'account_number',
        'account_owner'
    ];

    protected $hidden = [
        'account_number'
    ];

    public function casts()
    {
        return [
            'type' => PaymentType::class,
        ];
    }

    protected function accountNumber(): Attribute{
        return Attribute::make(
            get: fn ($value) => $value ? Crypt::decrypt($value) : null,
            set: fn ($value) => $value ? Crypt::encrypt($value) : null,
        );
    }

    public function scopeFilter(Builder $query, array $filters): void{
        $query->when($filters['search'] ?? null, function($query, $search){
            $query->whereAny([
                'name',
                'type',
                'account_number',
                'account_owner'
            ], 'REGEXP', $search);
        });
    }

    public function scopeSorting(Builder $query, array $sorts): void{
        $query->when($sorts['field'] ?? null && $sorts['direction'], function($query) use($sorts){
            $query->orderBy($sorts['field'], $sorts['direction']);
        });
    }
}
