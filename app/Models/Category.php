<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes, Sluggable;
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'desc',
        'type',
        'user_id'
    ];

    public function comics(): BelongsToMany
    {
        return $this->belongsToMany(Comic::class, 'category_comic', 'category_id', 'comic_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function setNameAttribute($input)
    {
        return $this->attributes['name'] = mb_convert_case($input, MB_CASE_TITLE, 'UTF-8');
    }

    public function setDescAttribute($input)
    {
        return $this->attributes['desc'] = mb_strtoupper(mb_substr($input, 0, 1)) . mb_substr($input, 1); //ucfirst with UTF-8
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
