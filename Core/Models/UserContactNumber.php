<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserContactNumber extends Model
{
    protected $table = 'usercontactnumber';
    public $timestamps = false ;
    protected $fillable = ['idUser','mobile','codeP','active','isoP','fullNumber','isID'];


}
