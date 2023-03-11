<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = [
        'type',
        'type_id',
        'parent_id',
        'body',
        'user_id'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'type_id', 'id');
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class, 'type_id', 'id');
    }
}
