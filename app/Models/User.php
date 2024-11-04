<?php

namespace App\Models;

use Core\Enum\StatusRequest;
use Core\Models\identificationuserrequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Client\Request;
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
    const DEFAULT_PROFILE_URL = 'uploads/profiles/default.png';
    const DEFAULT_NATIONAL_FRONT_URL = 'uploads/profiles/front-id-image.png';
    const DEFAULT_NATIONAL_BACK_URL = 'uploads/profiles/back-id-image.png';
    const DEFAULT_INTERNATIONAL_URL = 'uploads/profiles/international-id-image.png';
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

    public static function getUserProfileImage($idUser)
    {
        $accountUser = User::where('idUser', $idUser)->first();
        try {
            return url($accountUser->profileImage()->first()->url);
        } catch (\Exception $exception) {
            return self::DEFAULT_PROFILE_URL;
        }
    }


    public static function getNationalFrontImage($idUser)
    {
        $accountUser = User::where('idUser', $idUser)->first();
        try {
            return url($accountUser->nationalIdentitieFrontImage()->first()->url);

        } catch (\Exception $exception) {
            return self::DEFAULT_NATIONAL_FRONT_URL;
        }
    }

    public static function getNationalBackImage($idUser)
    {
        $accountUser = User::where('idUser', $idUser)->first();
        try {
            return url($accountUser->nationalIdentitieBackImage()->first()->url);
        } catch (\Exception $exception) {
            return self::DEFAULT_NATIONAL_BACK_URL;
        }
    }

    public static function getInternational($idUser)
    {
        $accountUser = User::where('idUser', $idUser)->first();
        try {
            return url($accountUser->internationalIdentitieImage()->first()->url);
        } catch (\Exception $exception) {
            return self::DEFAULT_INTERNATIONAL_URL;
        }
    }

    public static function saveProfileImage($idUser, $imageProfil)
    {
        Image::ValidateImage($imageProfil);
        $imageProfil->storeAs('profiles', 'profile-image-' . $idUser . '.' . $imageProfil->extension(), 'public2');

        $image = Image::create(['type' => self::IMAGE_TYPE_PROFILE, 'url' => 'uploads/profiles/profile-image-' . $idUser . '.' . $imageProfil->extension()]);
        $user = User::where('idUser', $idUser)->first();
        if ($user->profileImage()->where('type', '=', self::IMAGE_TYPE_PROFILE)->exists()) {
            $user->profileImage()->where('type', '=', self::IMAGE_TYPE_PROFILE)->first()->delete();
        }

        $user->profileImage()->save($image);
    }

    public static function saveNationalFrontImage($idUser, $imageNationalFront)
    {
        Image::ValidateImage($imageNationalFront);
        $imageNationalFront->storeAs('profiles', Image::IMAGE_PREFIX_FRONT . $idUser . '.' . $imageNationalFront->extension(), 'public2');

        $image = Image::create([
            'type' => self::IMAGE_TYPE_NATIONAL_FRONT, 'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_FRONT . $idUser . '.' . $imageNationalFront->extension()]);
        $user = User::where('idUser', $idUser)->first();
        if ($user->nationalIdentitieFrontImage()->where('type', '=', self::IMAGE_TYPE_NATIONAL_FRONT)->exists()) {
            $user->nationalIdentitieFrontImage()->where('type', '=', self::IMAGE_TYPE_NATIONAL_FRONT)->first()->delete();
        }

        $user->nationalIdentitieFrontImage()->save($image);
    }

    public static function saveNationalBackImage($idUser, $imageNationalBack)
    {
        Image::ValidateImage($imageNationalBack);
        $imageNationalBack->storeAs('profiles', Image::IMAGE_PREFIX_BACK . $idUser . '.' . $imageNationalBack->extension(), 'public2');

        $image = Image::create(['type' => self::IMAGE_TYPE_NATIONAL_BACK, 'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_BACK . $idUser . '.' . $imageNationalBack->extension()]);
        $user = User::where('idUser', $idUser)->first();
        if ($user->nationalIdentitieBackImage()->where('type', '=', self::IMAGE_TYPE_NATIONAL_BACK)->exists()) {
            $user->nationalIdentitieBackImage()->where('type', '=', self::IMAGE_TYPE_NATIONAL_BACK)->first()->delete();
        }

        $user->nationalIdentitieBackImage()->save($image);
    }

    public static function saveInternationalImage($idUser, $imageInternational)
    {
        Image::ValidateImage($imageInternational);
        $imageInternational->storeAs('profiles', Image::IMAGE_PREFIX_INTERNATIONAL . $idUser . '.' . $imageInternational->extension(), 'public2');

        $image = Image::create(['type' => self::IMAGE_TYPE_INTERNATIONAL, 'url' => Image::IMAGE_PROFILE_PATH . Image::IMAGE_PREFIX_INTERNATIONAL . $idUser . '.' . $imageInternational->extension()]);
        $user = User::where('idUser', $idUser)->first();
        if ($user->internationalIdentitieImage()->where('type', '=', self::IMAGE_TYPE_INTERNATIONAL)->exists()) {
            $user->internationalIdentitieImage()->where('type', '=', self::IMAGE_TYPE_INTERNATIONAL)->first()->delete();
        }

        $user->internationalIdentitieImage()->save($image);
    }
}
