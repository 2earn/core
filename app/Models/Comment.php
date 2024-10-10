<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'validated',
        'validatedBy_id',
        'validatedAt',
        'user_id',
    ];

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validatedBy_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function validate($idComment)
    {
        Comment::findOrFail($idComment)->update(['validated' => true, 'validatedBy_id' => auth()->user()->id, 'validatedAt' => Carbon::now()]);
        return true;
    }

    public static function deleteComment($idComment)
    {
        Comment::findOrFail($idComment)->delete();
        return true;
    }
}
