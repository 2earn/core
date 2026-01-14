<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class MettaUser extends Model
{
    use HasAuditing;

    protected $table = 'metta_users';
    public $timestamps = true;

    protected $fillable = [
        'idUser',
        'arFirstName',
        'arLastName',
        'enFirstName',
        'enLastName',
        'personaltitle',
        'idCountry',
        'childrenCount',
        'birthday',
        'gender',
        'email',
        'secondEmail',
        'idLanguage',
        'nationalID',
        'internationalISD',
        'adresse',
        'idState',
        'note',
        'interests',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'birthday' => 'date',
        'childrenCount' => 'integer',
        'idCountry' => 'integer',
        'idState' => 'integer',
    ];

    /**
     * Get the user that owns this metta user record
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    /**
     * Get the full name in English
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $firstName = $this->enFirstName ?? '';
        $lastName = $this->enLastName ?? '';
        return trim($firstName . ' ' . $lastName);
    }

    /**
     * Get the full name in Arabic
     *
     * @return string
     */
    public function getFullNameArAttribute(): string
    {
        $firstName = $this->arFirstName ?? '';
        $lastName = $this->arLastName ?? '';
        return trim($firstName . ' ' . $lastName);
    }
}

