<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class CommittedInvestorRequest extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'status',
        'note',
        'request_date',
        'examination_date',
        'user_id',
        'examiner_id',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function examiner()
    {
        return $this->belongsTo(User::class, 'examiner_id', 'id');
    }
}
