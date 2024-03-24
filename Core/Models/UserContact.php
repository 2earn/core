<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $table = 'user_contacts';
    public $timestamps = false;
    protected $fillable = ['idUser', 'name', 'lastName', 'mobile', 'availablity', 'disponible', 'fullphone_number', 'phonecode'];


    public function checkavailablity()
    {

    }
}
