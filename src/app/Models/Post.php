<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['published_at', 'deleted_at'];

    public function rels()
    {
        return $this->hasMany('ZetthCore\Models\PostTerm');
    }

    public function terms()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms');
    }

    public function categories()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms')->where('type', 'category');
    }

    public function tags()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms')->where('type', 'tag');
    }

    public function author()
    {
        return $this->belongsTo('ZetthCore\Models\User', 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo('ZetthCore\Models\User', 'updated_by');
    }

    public function comments()
    {
        return $this->hasMany('ZetthCore\Models\PostComment')->where('status', 1);
    }

    public function comments_sub()
    {
        return $this->hasMany('ZetthCore\Models\PostComment')->where('status', 1)->with('subcomments');
    }

    public function comments_all()
    {
        return $this->hasMany('ZetthCore\Models\PostComment');
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
}
