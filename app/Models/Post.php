<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    public $guarded = [];
    public function comments()
    {
        return $this->hasMany(Comment::class,'on_post');
    }

    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}
