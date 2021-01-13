<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function likes()
    {
        return $this->morphToMany(User::class,'likeable');
    }

    public function savesPosts()
    {
        return $this->belongsToMany(User::class,'save_posts');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
