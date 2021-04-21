<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    protected $table = 'r_restau';
    // protected $primaryKey = 'idre';

    public function images()
    {
        return $this->hasMany('App\Models\RestauranteImage', 'idre');
    }

    // NO FUNCIONA
    public function platos()
    {
        return $this->hasMany('App\Models\RestaurantePlato', 'idcp');
    }
}
