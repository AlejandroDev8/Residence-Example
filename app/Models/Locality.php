<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = [
        'name',
        'municipality_id',
        'cve_loc',
        'cve_geo',
        'urban_area',
        'lat',
        'lng'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }
}
