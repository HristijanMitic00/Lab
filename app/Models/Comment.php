<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $guarded = [];

    public function author()
    {
        return $this->belongsTo('App\Models','from_user');
    }

    public function post()
    {
        return $this->belongsTo('App\Models','on_post');
    }
}
