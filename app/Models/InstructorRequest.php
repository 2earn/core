<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'note',
        'request_date',
        'examination_date',
        'user_id',
        'examiner_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_id', 'id');
    }
}
