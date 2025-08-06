<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'platform',
        'name',
        'domain',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(StoreToken::class);
    }

    public function productCaches(): HasMany
    {
        return $this->hasMany(ProductCache::class);
    }

    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class);
    }
}
