<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasAuditing;

class UserGuide extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'user_id',
        'routes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'routes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
