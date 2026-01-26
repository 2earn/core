<?php

namespace App\Services;

use App\Enums\StatusRequest;
use App\Interfaces\IUserRepository;
use App\Models\AuthenticatedUser;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private IUserRepository $userRepository
    )
    {
    }
    /**
     * Get paginated users with joins and filters.
     *
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUsers(?string $search, string $sortBy, string $sortDirection, int $perPage): LengthAwarePaginator
    {
        $query = User::select(
            'countries.apha2',
            'countries.name as country',
            'users.id',
            'users.status',
            'users.idUser',
            'idUplineRegister',
            DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName)) AS name'),
            'users.mobile',
            'users.created_at',
            'OptActivation',
            'activationCodeValue',
            'pass',
            DB::raw('IFNULL(`vip`.`flashCoefficient`,"##") as coeff'),
            DB::raw('IFNULL(`vip`.`flashDeadline`,"##") as periode'),
            DB::raw('IFNULL(`vip`.`flashNote`,"##") as note'),
            DB::raw('IFNULL(`vip`.`flashMinAmount`,"##") as minshares'),
            DB::raw('IFNULL(`vip`.`dateFNS`,"##") as date'),
        )
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry')
            ->leftJoin('vip', function ($join) {
                $join->on('vip.idUser', '=', 'users.idUser')->where('vip.closed', '=', 0);
            });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.mobile', 'like', '%' . $search . '%')
                    ->orWhere('users.idUser', 'like', '%' . $search . '%')
                    ->orWhere(DB::raw('CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), " ", COALESCE(meta.arLastName, meta.enLastName))'), 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy($sortBy, $sortDirection)->paginate($perPage);
    }

    /**
     * Get public users for financial requests
     *
     * @param int $excludeUserId User ID to exclude from results
     * @param int $countryId Country ID filter
     * @param int $minStatus Minimum status value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPublicUsers(int $excludeUserId, int $countryId, int $minStatus)
    {
        return User::where('is_public', 1)
            ->where('idUser', '<>', $excludeUserId)
            ->where('idCountry', $countryId)
            ->where('status', '>', $minStatus)
            ->get();
    }

    /**
     * Find user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Update user OTP activation
     *
     * @param int $userId
     * @param string $optCode
     * @return int Number of rows updated
     */
    public function updateOptActivation(int $userId, string $optCode): int
    {
        return User::where('id', $userId)->update(['OptActivation' => $optCode]);
    }

    /**
     * Update user with custom data
     *
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool
    {
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        return $user->save();
    }

    /**
     * Find user by idUser (business ID)
     *
     * @param string $idUser
     * @return object|null
     */
    public function findByIdUser(string $idUser): ?object
    {
        return DB::table('users')->where('idUser', $idUser)->first();
    }

    /**
     * Update user password by user ID
     *
     * @param int $userId
     * @param string $hashedPassword
     * @return int Number of rows updated
     */
    public function updatePassword(int $userId, string $hashedPassword): int
    {
        return DB::table('users')->where('id', $userId)->update(['password' => $hashedPassword]);
    }

    /**
     * Update user fields by user ID
     *
     * @param int $userId
     * @param array $data
     * @return int Number of rows updated
     */
    public function updateById(int $userId, array $data): int
    {
        return DB::table('users')->where('id', $userId)->update($data);
    }

    /**
     * Update user activation code value
     *
     * @param int $userId
     * @param string $activationCodeValue
     * @return int Number of rows updated
     */
    public function updateActivationCodeValue(int $userId, string $activationCodeValue): int
    {
        return User::where('id', $userId)->update(['activationCodeValue' => $activationCodeValue]);
    }

    /**
     * Get users list query for datatable
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getUsersListQuery()
    {
        return User::select('countries.apha2', 'countries.name as country', 'users.id', 'users.status', 'users.idUser', 'idUplineRegister',
            DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS name'),
            'users.mobile', 'users.created_at', 'OptActivation', 'activationCodeValue', 'pass',
            DB::raw('IFNULL(`vip`.`flashCoefficient`,"##") as coeff'),
            DB::raw('IFNULL(`vip`.`flashDeadline`,"##") as periode'),
            DB::raw('IFNULL(`vip`.`flashNote`,"##") as note'),
            DB::raw('IFNULL(`vip`.`flashMinAmount`,"##") as minshares'),
            DB::raw('`vip`.`dateFNS` as date'))
            ->join('metta_users as meta', 'meta.idUser', '=', 'users.idUser')
            ->join('countries', 'countries.id', '=', 'users.idCountry')
            ->leftJoin('vip', function ($join) {
                $join->on('vip.idUser', '=', 'users.idUser')->where('vip.closed', '=', 0);
            })->orderBy('created_at', 'DESC');
    }

    /**
     * Get authenticated user by ID with metta user data
     *
     * @param int|string $id User ID
     * @return AuthenticatedUser|null
     */
    public function getAuthUserById($id): ?AuthenticatedUser
    {
        $user = $this->userRepository->getUserById($id);

        if (!$user) {
            $user = User::where('idUser', $id)->first();
            if (!$user) {
                return null;
            }
        }

        $userMetta = $this->userRepository->getAllMettaUser()
            ->where('idUser', '=', $user->idUser)
            ->first();

        $userAuth = new AuthenticatedUser();
        $userAuth->id = $user->id;
        $userAuth->idUser = $user->idUser;
        $userAuth->mobile = $user->mobile;
        $userAuth->arFirstName = $userMetta->arFirstName ?? null;
        $userAuth->arLastName = $userMetta->arLastName ?? null;
        $userAuth->enFirstName = $userMetta->enFirstName ?? null;
        $userAuth->enLastName = $userMetta->enLastName ?? null;
        $userAuth->iden_notif = $user->iden_notif;

        return $userAuth;
    }

    /**
     * Get new validated status based on current user status
     *
     * @param string $idUser
     * @return int|null
     */
    public function getNewValidatedstatus(string $idUser): ?int
    {
        $user = User::where('idUser', $idUser)->first();

        if (!$user) {
            return null;
        }

        if ($user->status == StatusRequest::InProgressNational->value) {
            return StatusRequest::ValidNational->value;
        }

        if ($user->status == StatusRequest::InProgressInternational->value || $user->status == StatusRequest::InProgressGlobal->value) {
            return StatusRequest::ValidInternational->value;
        }


        return null;
    }

    /**
     * Create a new user with all necessary fields
     *
     * @param array $data
     * @return int|null Returns the user ID or null on failure
     */
    public function createUser(array $data): ?int
    {
        try {
            $user = User::create($data);
            return $user->id;
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Search users by multiple criteria (name, email, phone, idUser)
     *
     * @param string $searchTerm Search term
     * @param int $limit Maximum number of results
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchUsers(string $searchTerm, int $limit = 10)
    {
        if (empty($searchTerm)) {
            return User::whereRaw('1 = 0')->get(); // Return empty Eloquent Collection
        }

        return User::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email', 'like', '%' . $searchTerm . '%')
            ->orWhere('idUser', 'like', '%' . $searchTerm . '%')
            ->orWhereHas('mettaUser', function ($query) use ($searchTerm) {
                $query->where('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('secondEmail', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('contactUser', function ($query) use ($searchTerm) {
                $query->where('mobile', 'like', '%' . $searchTerm . '%')
                    ->orWhere('fullphone_number', 'like', '%' . $searchTerm . '%');
            })
            ->limit($limit)
            ->get();
    }

    /**
     * Get user by ID with entity roles
     *
     * @param int $userId
     * @return User|null
     */
    public function getUserWithRoles(int $userId): ?User
    {
        return User::with(['entityRoles' => function ($query) {
            $query->select('id', 'user_id', 'name', 'roleable_id', 'roleable_type')
                  ->with('roleable:id,name');
        }])->find($userId);
    }

    /**
     * Save user profile settings (profile image and visibility)
     *
     * @param int $userId User ID
     * @param bool $isPublic Profile visibility setting
     * @param mixed|null $imageProfil Uploaded profile image file
     * @return array Result array with success status and message
     */
    public function saveProfileSettings(int $userId, bool $isPublic, $imageProfil = null): array
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found'
                ];
            }

            // Handle profile image upload if provided
            if (!is_null($imageProfil)) {
                User::saveProfileImage($user->idUser, $imageProfil);
            }

            // Update visibility setting
            $user->is_public = $isPublic;
            $user->save();

            return [
                'success' => true,
                'message' => 'Profile settings saved successfully',
                'userProfileImage' => User::getUserProfileImage($user->idUser)
            ];

        } catch (\Exception $exception) {
            Log::error('Error saving profile settings: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error saving profile settings'
            ];
        }
    }

    /**
     * Save complete user profile including metta user data
     *
     * @param int $userId User ID
     * @param int $mettaUserId Metta User ID
     * @param array $mettaUserData Metta user data to update
     * @param int $nbrChild Number of children
     * @param bool $isPublic Profile visibility
     * @param string $paramIdUser Parameter ID user for admin validation
     * @param mixed|null $imageProfil Profile image to upload
     * @return array Result array with success status, message, redirect route and user
     */
    public function saveUserProfile(
        int $userId,
        int $mettaUserId,
        array $mettaUserData,
        int $nbrChild,
        bool $isPublic,
        string $paramIdUser = "",
        $imageProfil = null
    ): array
    {
        try {
            $user = User::find($userId);
            $mettaUser = \App\Models\MettaUser::find($mettaUserId);

            if (!$user || !$mettaUser) {
                return [
                    'success' => false,
                    'message' => 'User or Metta User not found',
                    'redirectRoute' => 'account'
                ];
            }

            // Check if user can modify their profile
            if ($paramIdUser == "" && $user->hasIdentificationRequest()) {
                return [
                    'success' => false,
                    'message' => 'You cant update your profile when you have an identifiaction request in progress',
                    'redirectRoute' => 'account',
                    'flashType' => 'info'
                ];
            }

            // Update metta user profile data
            $mettaUser->arLastName = $mettaUserData['arLastName'] ?? $mettaUser->arLastName;
            $mettaUser->arFirstName = $mettaUserData['arFirstName'] ?? $mettaUser->arFirstName;
            $mettaUser->enLastName = $mettaUserData['enLastName'] ?? $mettaUser->enLastName;
            $mettaUser->enFirstName = $mettaUserData['enFirstName'] ?? $mettaUser->enFirstName;

            if (!empty($mettaUserData['birthday'])) {
                $mettaUser->birthday = $mettaUserData['birthday'];
            } else {
                $mettaUser->birthday = null;
            }

            $mettaUser->adresse = $mettaUserData['adresse'] ?? $mettaUser->adresse;
            $mettaUser->nationalID = $mettaUserData['nationalID'] ?? $mettaUser->nationalID;

            // Validate and set children count
            $nbrChild = max(0, min(20, $nbrChild));
            $mettaUser->childrenCount = $nbrChild;

            // Set state
            if (!empty($mettaUserData['idState'])) {
                $mettaUser->idState = $mettaUserData['idState'];
            } else {
                $mettaUser->idState = null;
            }

            $mettaUser->gender = $mettaUserData['gender'] ?? $mettaUser->gender;
            $mettaUser->personaltitle = $mettaUserData['personaltitle'] ?? $mettaUser->personaltitle;
            $mettaUser->idLanguage = $mettaUserData['idLanguage'] ?? $mettaUser->idLanguage;

            // Update user status if admin is validating
            if ($paramIdUser != "") {
                $user->status = \App\Enums\StatusRequest::InProgressNational->value;
            }

            $mettaUser->save();

            // Update user visibility
            $user->is_public = $isPublic;
            $user->save();

            // Handle profile image upload
            if (!is_null($imageProfil)) {
                User::saveProfileImage($user->idUser, $imageProfil);
            }

            return [
                'success' => true,
                'message' => 'Edit profile success',
                'redirectRoute' => $paramIdUser == "" ? 'account' : 'requests_identification',
                'user' => $user,
                'shouldValidateIdentity' => $paramIdUser != ""
            ];

        } catch (\Exception $exception) {
            Log::error('Error saving user profile: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'redirectRoute' => 'account'
            ];
        }
    }
}


