<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaizeSubSample extends Model
{
    protected $fillable = [
        'maize_sample_id',
        'subsample_number',
        'color_grano',
        'color_olote',
        'tipo_grano',
        'forma_corona_grano',
        'color_dorsal_grano',
        'color_endospermo_grano',
        'arreglo_hileras_grano',
        'diametro_mazorca_mm',
        'largo_mazorca_mm',
        'peso_mazorca_g',
        'peso_grano_50_g',
        'num_hileras',
        'num_granos_por_hilera',
        'grosor_grano_mm',
        'ancho_grano_mm',
        'longitud_grano_mm',
        'indice_lgr_agr',
        'volumen_grano_50_ml',
    ];
}
