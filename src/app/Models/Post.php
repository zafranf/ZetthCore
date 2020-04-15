<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['published_at', 'deleted_at'];
    public $appends = ['published_string'];

    public function terms()
    {
        return $this->morphToMany('ZetthCore\Models\Term', 'termable');
    }

    public function categories()
    {
        return $this->morphToMany('ZetthCore\Models\Term', 'termable')->where('type', 'category');
    }

    public function tags()
    {
        return $this->morphToMany('ZetthCore\Models\Term', 'termable')->where('type', 'tag');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function comments()
    {
        return $this->morphMany('ZetthCore\Models\Comment', 'commentable')->where('status', 1);
    }

    public function comments_sub()
    {
        return $this->morphMany('ZetthCore\Models\Comment', 'commentable')->where('status', 1)->whereNull('parent_id')->with(['subcomments', 'commentator']);
    }

    public function comments_all()
    {
        return $this->morphMany('ZetthCore\Models\Comment', 'commentable');
    }

    public function likes()
    {
        return $this->morphMany('App\Models\Like', 'likeable');
    }

    public function likes_user()
    {
        return $this->morphOne('App\Models\Like', 'likeable')->where('user_id', app('user')->id ?? null);
    }

    public function scopePosts($query)
    {
        return $query->where('type', 'article');
    }

    public function scopeArticles($query)
    {
        return $query->where('type', 'article');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopePages($query)
    {
        return $query->where('type', 'page');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeWithCategory($query, $name)
    {
        return $query->whereHas('terms', function ($q) use ($name) {
            $q->where('type', 'category')->where('slug', $name);
        });
    }

    public function scopeWithCategories($query, $name)
    {
        return $query->whereHas('terms', function ($q) use ($name) {
            $q->where('type', 'category')->whereIn('slug', $name);
        });
    }

    public function scopeWithTag($query, $name)
    {
        return $query->whereHas('terms', function ($q) use ($name) {
            $q->where('type', 'tag')->where('slug', $name);
        });
    }

    public function scopeWithTags($query, $name)
    {
        return $query->whereHas('terms', function ($q) use ($name) {
            $q->where('type', 'category')->whereIn('slug', $name);
        });
    }

    public function scopeWithAuthor($query, $name)
    {
        return $query->whereHas('author', function ($q) use ($name) {
            $q->where('name', $name);
        });
    }

    public function getPublishedStringAttribute()
    {
        return generateDate($this->published_at, app('site')->language, 'Do MMMM YYYY HH:mm');
    }
}
