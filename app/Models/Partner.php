<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Partner extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'company_name',
        'business_sector',
        'platform_url',
        'platform_description',
        'partnership_reason',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user who created this partner
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this partner
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}

