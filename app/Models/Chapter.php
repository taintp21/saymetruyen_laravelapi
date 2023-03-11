<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';
    protected $fillable = [
        'comic_id',
        'name',
        'chapter_no',
        'image_paths',
        'view'
    ];

    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'type_id', 'id');
    }
}
