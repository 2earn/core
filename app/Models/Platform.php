<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Platform extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'description',
        'enabled',
        'type',
        'link',
        'show_profile',
        'image_link',
        'business_sector_id',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;
    const IMAGE_TYPE_LOGO = 'logo';

    const DEFAULT_IMAGE_TYPE_LOGO = 'resources/images/platforms/logo for platform.png';

    public function marketingManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function financialManager(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function businessSector(): HasOne
    {
        return $this->hasOne(BusinessSector::class, 'id', 'business_sector_id');
    }

    public function productDealHistory(): HasMany
    {
        return $this->hasMany(ProductDealHistory::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function commissionBreakdowns()
    {
        return $this->hasMany(\App\Models\CommissionBreakDown::class);
    }

    public function logoImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', '=', self::IMAGE_TYPE_LOGO);
    }

    public function typeChangeRequests(): HasMany
    {
        return $this->hasMany(PlatformTypeChangeRequest::class);
    }

    public function pendingTypeChangeRequest()
    {
        return $this->hasOne(PlatformTypeChangeRequest::class)->where('status', 'pending')->latest();
    }

    public function validationRequests(): HasMany
    {
        return $this->hasMany(PlatformValidationRequest::class);
    }

    public function validationRequest()
    {
        return $this->hasOne(PlatformValidationRequest::class)->latest();
    }

    public function pendingValidationRequest()
    {
        return $this->hasOne(PlatformValidationRequest::class)->where('status', 'pending')->latest();
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(PlatformChangeRequest::class);
    }

    public function pendingChangeRequest()
    {
        return $this->hasOne(PlatformChangeRequest::class)->where('status', 'pending')->latest();
    }

    public function platformRoleAssignments(): HasMany
    {
        return $this->hasMany(AssignPlatformRole::class);
    }

    /**
     * Get all entity roles (polymorphic)
     */
    public function roles()
    {
        return $this->morphMany(EntityRole::class, 'roleable');
    }

    /**
     * Get all users with roles in this platform
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlatformRoleUsers()
    {
        $userIds = $this->roles()->pluck('user_id')->unique()->filter();

        if ($userIds->isEmpty()) {
            return User::whereIn('id', [])->get(); // Returns empty Eloquent Collection
        }

        return User::whereIn('id', $userIds)->get();
    }

    public function selected($idUser = null)
    {
        if (is_null($idUser)) {
            return false;
        }

        $platforms = DB::table('user_plateforme')->where('user_id', $idUser)->get();
        if (is_null($platforms)) {
            return false;
        }
        return $platforms->where("plateforme_id", $this->id)->first() ? true : false;
    }


    public static function havePartnerSpecialRole($id)
    {
        if (User::isSuperAdmin()) {
            return true;
        }
        return DB::table('entity_roles')
            ->where('user_id', $id)
            ->where('roleable_type', 'App\\Models\\Platform')
            ->exists();
    }

}
