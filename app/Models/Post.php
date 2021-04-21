<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $table = "posts";

    public function images()
    {
        return $this->hasMany('App\Models\PostImage');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
