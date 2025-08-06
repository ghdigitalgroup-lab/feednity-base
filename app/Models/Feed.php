<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'channel',
        'format',
        'status',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(FeedRule::class);
    }

    public function runs(): HasMany
    {
        return $this->hasMany(FeedRun::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(FeedFile::class);
    }
}
