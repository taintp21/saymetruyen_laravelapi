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
        'chapter_no',
        'view'
    ];

    public function comic()
    {
        return $this->belongsTo(Comic::class, 'comic_id', 'id');
    }
}
