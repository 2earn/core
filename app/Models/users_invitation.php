<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class users_invitation extends Model
{
    protected $table = 'users_invitations';
    public $timestamps = false ;
    protected $fillable = ['mobile','codePaye','fullNumber','dateDebut','dateFin','periode'];
}
