<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turismo extends Model
{
    protected $table = 'tu_turismo';
    protected $primaryKey = 'idtu';

    public function images()
    {
        return $this->hasMany('App\Models\TurismoFotos', 'idtu');
    }

    public function operadores()
    {
        return $this->belongsToMany('App\Models\MOperador', 'tu_turismo_ope', 'idtu', 'idop');
    }

    public function servicios()
    {
        return $this->belongsToMany('App\Models\MServicio', 'tu_turismo_ser', 'idtu', 'idse');
    }
    
    public function actividades()
    {
        return $this->belongsToMany('App\Models\MActividad', 'tu_turismo_acti', 'idtu', 'idac');
    }
}
