<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function savesPosts()
    {
        return $this->belongsToMany(User::class, 'save_posts');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function mentions()
    {
        return $this->morphToMany(User::class, 'mention');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::random(11);
    }

    public function setCaptionAttribute($value)
    {
        $this->attributes['caption'] = $value;
        $this->slug ?: $this->slug = 0;
    }
}
