<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    use HasFactory;
    protected $table = 'comics';
    protected $fillable = [
        'name',
        'slug',
        'author',
        'desc',
        'background_preview',
        'image_preview',
        'status',
        'user_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_comic', 'comic_id', 'category_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'comic_id', 'id');
    }

    public function setNameAttribute($input)
    {
        return $this->attributes['name'] = mb_convert_case($input, MB_CASE_TITLE, 'UTF-8');
    }

    public function setAuthorAttribute($input)
    {
        return $this->attributes['author'] = mb_convert_case($input, MB_CASE_TITLE, 'UTF-8');
    }
}
