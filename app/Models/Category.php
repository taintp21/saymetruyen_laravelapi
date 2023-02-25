<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'desc',
        'type',
        'user_id'
    ];
    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'category_comic', 'category_id', 'comic_id');
    }

    public function posts()
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
}
