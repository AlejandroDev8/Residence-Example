<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'cve_ent',
        'name',
        'abbreviation'
    ];

    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }
}
