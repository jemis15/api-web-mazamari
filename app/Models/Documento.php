<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'p_seccion';
    protected $primaryKey = 'ids';

    public function files()
    {
        return $this->hasMany('App\Models\File', 'ids', 'ids');
    }
}
