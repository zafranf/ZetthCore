<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function rels()
    {
        return $this->hasMany('ZetthCore\Models\PostTerm');
    }

    public function terms()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms', 'post_id', 'term_id');
    }

    public function categories()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms', 'post_id', 'term_id')->where('type', 'category');
    }

    public function tags()
    {
        return $this->belongsToMany('ZetthCore\Models\Term', 'post_terms', 'post_id', 'term_id')->where('type', 'tag');
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
        return $this->hasMany('ZetthCore\Models\PostComment', 'post_id')->where('comment_status', 1);
    }

    public function comments2()
    {
        return $this->hasMany('ZetthCore\Models\PostComment', 'post_id');
    }
}
