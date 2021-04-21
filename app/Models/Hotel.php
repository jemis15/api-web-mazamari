<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'h_hotel';
    protected $primaryKey = 'idh';

    public function images()
    {
        return $this->hasMany('App\Models\HotelImage', 'idh');
    }

    public function habitaciones()
    {
        return $this->belongsToMany('App\Models\HotelHabitacion', 'h_hotel_habi', 'idh','idha');
    }
}
