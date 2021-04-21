<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agroindustria extends Model
{
    protected $table = 'pro_agro';
    protected $primaryKey = 'idpro';
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany('App\Models\AgroindustriaImage', 'idpro');
    }
}
