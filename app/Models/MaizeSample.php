<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaizeSample extends Model
{
    protected $fillable = [
        'user_id',
        'farmer_id',
        'municipality_id',
        'locality_id',
        'code',
        'sample_number',
        'collection_date',
        'latitude',
        'longitude',
        'variety_name',
        'notes',
    ];

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }
    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function subsamples(): HasMany
    {
        return $this->hasMany(MaizeSubSample::class);
    }
}
