<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class ContactUser extends Model
{
    use HasFactory, HasAuditing;

    protected $table = 'contact_users';
    public $timestamps = true;

    protected $fillable = ['idUser', 'idContact', 'name', 'lastName', 'mobile', 'availablity', 'disponible', 'fullphone_number', 'phonecode',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function contact()
    {
        return $this->belongsTo(User::class, 'idContact');
    }
}
