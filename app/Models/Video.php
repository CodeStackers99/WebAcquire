<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    public const LIKE_CONST = 1;
    public const WATCH_LATER = 1;
    public const NOT_WATCH_LATER = 0;

    protected $guarded = ['id'];

    //MUTATORS
    public function setTitleAttribute(String $title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }

    //GETTERS
    public function getUrlAttribute()
    {
        return $this->playlist->url . "/videos/{$this->slug}";
    }
    public function getVideoPathAttribute()
    {
        return "storage/" . $this->video;
    }
    public function getImagePathAttribute()
    {
        return "storage/" . $this->display_image;
    }
    //SCOPES
    public function scopeSearch($query)
    {
        $search = request('search');
        if ($search) {
            return $query->where("title", "like", "%$search%");
        }
        return $query;
    }

    //RELATIONSHIPS
    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withPivot(['is_watch_later', 'reactions']);
    }
    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
