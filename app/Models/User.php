<?php

namespace App\Models;

use Core\Enum\StatusRequest;
use Core\Models\identificationuserrequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    const SUPER_ADMIN_ROLE_NAME = "Super admin";
    const MAX_PHOTO_ALLAWED_SIZE = 2048000;
    const PHOTO_ALLAWED_EXT = ['png', 'jpg', 'jpeg'];
    const IMAGE_TYPE_PROFILE = 'profile';
    const IMAGE_TYPE_NATIONAL_FRONT = 'national-front-image';
    const IMAGE_TYPE_NATIONAL_BACK = 'national-back-image';
    const IMAGE_TYPE_INTERNATIONAL = 'international';

    use HasApiTokens, HasFactory, Notifiable;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'purchasesNumber',
        'idLastUpline',
        'idReservedUpline',
        'commited_investor',
        'instructor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    public function hasIdentificationRequest()
    {
        $requestIdentification = identificationuserrequest::where('idUser', $this->idUser);
        $requestIdentification = $requestIdentification->where(function ($query) {
            $query->where('status', '=', StatusRequest::InProgressNational->value)
                ->orWhere('status', '=', StatusRequest::InProgressInternational->value);
        });

        return is_null($requestIdentification->get()->first()) ? false : true;
    }

    public function surveyResponse()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function profileImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_PROFILE);
    }

    public function nationalIdentitieFrontImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_NATIONAL_FRONT);
    }

    public function nationalIdentitieBackImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_NATIONAL_BACK);
    }

    public function internationalIdentitieImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_INTERNATIONAL);
    }

    public static function saveProfileImage($idUser, $imageProfil)
    {
        Image::ValidateImage($imageProfil);
        $imageProfil->storeAs('profiles', 'profile-image-' . $idUser . '.' . $imageProfil->extension(), 'public2');

        $image = Image::create([
            'type' => self::IMAGE_TYPE_PROFILE,
            'url' => 'uploads/profiles/profile-image-' . $idUser . '.' . $imageProfil->extension()
        ]);
        $user = User::where('idUser', $idUser)->first();
        if ($user->profileImage()->where('type', '=', self::IMAGE_TYPE_PROFILE)->exists()) {
            $user->profileImage()->where('type', '=', self::IMAGE_TYPE_PROFILE)->first()->delete();
        }

        $user->profileImage()->save($image);
    }
}
