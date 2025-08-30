<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [
        'name',
        'state_id',
        'cve_mun',
        'cve_geo'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function localities()
    {
        return $this->hasMany(Locality::class);
    }
}
