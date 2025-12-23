<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class PartnerRequest extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'company_name',
        'business_sector_id',
        'platform_url',
        'platform_description',
        'partnership_reason',
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

    public function businessSector()
    {
        return $this->belongsTo(BusinessSector::class, 'business_sector_id', 'id');
    }
}

