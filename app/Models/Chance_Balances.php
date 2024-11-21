<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chance_Balances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'actual_balance',
        'reference',
    ];

    public function platform()
    {
        return $this->hasOne(Platform::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

}
