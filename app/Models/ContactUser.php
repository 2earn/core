<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUser extends Model
{
    use HasFactory;

    protected $table = 'contact_users';
    public $timestamps = true;

    protected $fillable = ['idUser', 'idContact', 'name', 'lastName', 'mobile', 'availablity', 'disponible', 'fullphone_number', 'phonecode'];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function contact()
    {
        return $this->belongsTo(User::class, 'idContact');
    }
}
