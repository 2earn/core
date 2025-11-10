<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Traits\HasAuditing;

class Comment extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'content',
        'validated',
        'validatedBy_id',
        'validatedAt',
        'user_id',
    ];

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validatedBy_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function commentable()
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
