<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUser extends Model
{
    use HasFactory;

    protected $table = 'contact_users';
    public $timestamps = true;

    protected $fillable = ['idUser', 'name', 'lastName', 'mobile', 'availablity', 'disponible', 'fullphone_number', 'phonecode'];

}
