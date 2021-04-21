<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gastronomia extends Model
{
    protected $table = 'j_gastronomia';
    protected $primaryKey = 'idga';

    public function images()
    {
        return $this->hasMany('App\Models\GastronomiaImage', 'idga');
    }

    public function lugaresADegustar()
    {
        return $this->belongsToMany('App\Models\GastronomiaLugar', 'j_gastronomia_degustar', 'idga', 'idl');
    }
}
