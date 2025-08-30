<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = ['name', 'state_id', 'cve_mun', 'cve_geo', 'name'];
}
