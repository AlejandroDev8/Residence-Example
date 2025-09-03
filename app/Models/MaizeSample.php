<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
